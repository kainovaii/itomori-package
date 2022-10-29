<?php

namespace Itomori\Core;

require_once 'Controller.php';

class Router extends Controller
{
    /**
     * @var mixed afterRoutes
     */
    private $afterRoutes = [];

    /**
     * @var mixed beforeRoutes
     */
    private $beforeRoutes = [];

    /**
     * @var mixed notFoundCallback
     */
    protected $notFoundCallback = [];

    /**
     * @var mixed baseRoute
     */
    private $baseRoute = '';

    /**
     * @var mixed requestedMethod
     */
    private $requestedMethod = '';

    /**
     * @var mixed serverBasePath
     */
    private $serverBasePath;

    /**
     * @var mixed namespace
     */
    private $namespace = '';

    /**
     * before.
     *
     * @param mixed methods
     * @param mixed pattern
     * @param mixed fn
     *
     * @return void
     */
    public function before($methods, $pattern, $fn)
    {
        $pattern = $this->baseRoute.'/'.trim($pattern, '/');
        $pattern = $this->baseRoute ? rtrim($pattern, '/') : $pattern;

        foreach (explode('|', $methods) as $method) {
            $this->beforeRoutes[$method][] = [
                'pattern' => $pattern,
                'fn' => $fn,
            ];
        }
    }

    /**
     * match.
     *
     * @param mixed methods
     * @param mixed pattern
     * @param mixed fn
     *
     * @return void
     */
    public function match($methods, $pattern, $fn)
    {
        $pattern = $this->baseRoute.'/'.trim($pattern, '/');
        $pattern = $this->baseRoute ? rtrim($pattern, '/') : $pattern;

        foreach (explode('|', $methods) as $method) {
            $this->afterRoutes[$method][] = [
                'pattern' => $pattern,
                'fn' => $fn,
            ];
        }
    }

    /**
     * all.
     *
     * @param mixed pattern
     * @param mixed fn
     *
     * @return void
     */
    public function all($pattern, $fn)
    {
        $this->match('GET|POST|PUT|DELETE|OPTIONS|PATCH|HEAD', $pattern, $fn);
    }

    /**
     * get.
     *
     * @param mixed pattern
     * @param mixed fn
     *
     * @return void
     */
    public function get($pattern, $fn)
    {
        $this->match('GET', $pattern, $fn);
    }

    /**
     * post.
     *
     * @param mixed pattern
     * @param mixed fn
     *
     * @return void
     */
    public function post($pattern, $fn)
    {
        $this->match('POST', $pattern, $fn);
    }

    /**
     * patch.
     *
     * @param mixed pattern
     * @param mixed fn
     *
     * @return void
     */
    public function patch($pattern, $fn)
    {
        $this->match('PATCH', $pattern, $fn);
    }

    /**
     * delete.
     *
     * @param mixed pattern
     * @param mixed fn
     *
     * @return void
     */
    public function delete($pattern, $fn)
    {
        $this->match('DELETE', $pattern, $fn);
    }

    /**
     * put.
     *
     * @param mixed pattern
     * @param mixed fn
     *
     * @return void
     */
    public function put($pattern, $fn)
    {
        $this->match('PUT', $pattern, $fn);
    }

    /**
     * options.
     *
     * @param mixed pattern
     * @param mixed fn
     *
     * @return void
     */
    public function options($pattern, $fn)
    {
        $this->match('OPTIONS', $pattern, $fn);
    }

    /**
     * mount.
     *
     * @param mixed baseRoute
     * @param mixed fn
     *
     * @return void
     */
    public function mount($baseRoute, $fn)
    {
        $curBaseRoute = $this->baseRoute;

        $this->baseRoute .= $baseRoute;

        call_user_func($fn);

        $this->baseRoute = $curBaseRoute;
    }

    /**
     * getRequestHeaders.
     *
     * @return void
     */
    public function getRequestHeaders()
    {
        $headers = [];

        if (function_exists('getallheaders')) {
            $headers = getallheaders();

            if ($headers !== false) {
                return $headers;
            }
        }

        foreach ($_SERVER as $name => $value) {
            if ((substr($name, 0, 5) == 'HTTP_') || ($name == 'CONTENT_TYPE') || ($name == 'CONTENT_LENGTH')) {
                $headers[str_replace([' ', 'Http'], ['-', 'HTTP'], ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }

        return $headers;
    }

    /**
     * getRequestMethod.
     *
     * @return void
     */
    public function getRequestMethod()
    {
        $method = $_SERVER['REQUEST_METHOD'];

        if ($_SERVER['REQUEST_METHOD'] == 'HEAD') {
            ob_start();
            $method = 'GET';
        } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $headers = $this->getRequestHeaders();
            if (isset($headers['X-HTTP-Method-Override']) && in_array($headers['X-HTTP-Method-Override'], ['PUT', 'DELETE', 'PATCH'])) {
                $method = $headers['X-HTTP-Method-Override'];
            }
        }

        return $method;
    }

    /**
     * setNamespace.
     *
     * @param mixed namespace
     *
     * @return void
     */
    public function setNamespace($namespace)
    {
        if (is_string($namespace)) {
            $this->namespace = $namespace;
        }
    }

    /**
     * getNamespace.
     *
     * @return void
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * run.
     *
     * @param mixed callback
     *
     * @return void
     */
    public function run($callback = null)
    {
        $this->requestedMethod = $this->getRequestMethod();

        if (isset($this->beforeRoutes[$this->requestedMethod])) {
            $this->handle($this->beforeRoutes[$this->requestedMethod]);
        }

        $numHandled = 0;
        if (isset($this->afterRoutes[$this->requestedMethod])) {
            $numHandled = $this->handle($this->afterRoutes[$this->requestedMethod], true);
        }

        if ($numHandled === 0) {
            if (isset($this->afterRoutes[$this->requestedMethod])) {
                $this->render('error/404');
            }
        } elseif ($callback && is_callable($callback)) {
            $callback();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'HEAD') {
            ob_end_clean();
        }

        return $numHandled !== 0;
    }

    /**
     * set404.
     *
     * @param mixed match_fn
     * @param mixed fn
     *
     * @return void
     */
    public function set404($match_fn, $fn = null)
    {
        if (!is_null($fn)) {
            $this->notFoundCallback[$match_fn] = $fn;
        } else {
            $this->notFoundCallback['/'] = $match_fn;
        }
    }

    /**
     * trigger404.
     *
     * @param mixed match
     *
     * @return void
     */
    public function trigger404($match = null)
    {
        $numHandled = 0;

        if (count($this->notFoundCallback) > 0) {
            foreach ($this->notFoundCallback as $route_pattern => $route_callable) {
                $matches = [];

                $is_match = $this->patternMatches($route_pattern, $this->getCurrentUri(), $matches, PREG_OFFSET_CAPTURE);

                if ($is_match) {
                    $matches = array_slice($matches, 1);

                    $params = array_map(function ($match, $index) use ($matches) {
                        if (isset($matches[$index + 1]) && isset($matches[$index + 1][0]) && is_array($matches[$index + 1][0])) {
                            if ($matches[$index + 1][0][1] > -1) {
                                return trim(substr($match[0][0], 0, $matches[$index + 1][0][1] - $match[0][1]), '/');
                            }
                        }

                        return isset($match[0][0]) && $match[0][1] != -1 ? trim($match[0][0], '/') : null;
                    }, $matches, array_keys($matches));

                    $this->invoke($route_callable);

                    ++$numHandled;
                }
            }
        }
        if (($numHandled == 0) && (isset($this->notFoundCallback['/']))) {
            $this->invoke($this->notFoundCallback['/']);
        } elseif ($numHandled == 0) {
            header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found');
        }
    }

    /**
     * patternMatches.
     *
     * @param mixed pattern
     * @param mixed uri
     * @param mixed matches
     * @param mixed flags
     *
     * @return void
     */
    private function patternMatches($pattern, $uri, &$matches, $flags)
    {
        $pattern = preg_replace('/\/{(.*?)}/', '/(.*?)', $pattern);

        return boolval(preg_match_all('#^'.$pattern.'$#', $uri, $matches, PREG_OFFSET_CAPTURE));
    }

    /**
     * handle.
     *
     * @param mixed routes
     * @param mixed quitAfterRun
     *
     * @return void
     */
    private function handle($routes, $quitAfterRun = false)
    {
        $numHandled = 0;

        $uri = $this->getCurrentUri();

        foreach ($routes as $route) {
            $is_match = $this->patternMatches($route['pattern'], $uri, $matches, PREG_OFFSET_CAPTURE);

            if ($is_match) {
                $matches = array_slice($matches, 1);

                $params = array_map(function ($match, $index) use ($matches) {
                    if (isset($matches[$index + 1]) && isset($matches[$index + 1][0]) && is_array($matches[$index + 1][0])) {
                        if ($matches[$index + 1][0][1] > -1) {
                            return trim(substr($match[0][0], 0, $matches[$index + 1][0][1] - $match[0][1]), '/');
                        }
                    }

                    return isset($match[0][0]) && $match[0][1] != -1 ? trim($match[0][0], '/') : null;
                }, $matches, array_keys($matches));

                $this->invoke($route['fn'], $params);

                ++$numHandled;

                if ($quitAfterRun) {
                    break;
                }
            }
        }

        return $numHandled;
    }

    /**
     * invoke.
     *
     * @param mixed fn
     * @param mixed params
     *
     * @return void
     */
    private function invoke($fn, $params = [])
    {
        if (is_callable($fn)) {
            call_user_func_array($fn, $params);
        } elseif (stripos($fn, '@') !== false) {
            list($controller, $method) = explode('@', $fn);

            if ($this->getNamespace() !== '') {
                $controller = $this->getNamespace().'\\'.$controller;
            }

            try {
                $reflectedMethod = new \ReflectionMethod($controller, $method);

                if ($reflectedMethod->isPublic() && (!$reflectedMethod->isAbstract())) {
                    if ($reflectedMethod->isStatic()) {
                        forward_static_call_array([$controller, $method], $params);
                    } else {
                        if (\is_string($controller)) {
                            $controller = new $controller();
                        }
                        call_user_func_array([$controller, $method], $params);
                    }
                }
            } catch (\ReflectionException $reflectionException) {
            }
        }
    }

    /**
     * getCurrentUri.
     *
     * @return void
     */
    public function getCurrentUri()
    {
        $uri = substr(rawurldecode($_SERVER['REQUEST_URI']), strlen($this->getBasePath()));

        if (strstr($uri, '?')) {
            $uri = substr($uri, 0, strpos($uri, '?'));
        }

        return '/'.trim($uri, '/');
    }

    /**
     * getBasePath.
     *
     * @return void
     */
    public function getBasePath()
    {
        if ($this->serverBasePath === null) {
            $this->serverBasePath = implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1)).'/';
        }

        return $this->serverBasePath;
    }

    public function setBasePath($serverBasePath)
    {
        $this->serverBasePath = $serverBasePath;
    }
}
