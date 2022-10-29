<?php

namespace Itomori\Core;

use Predis\Client;

class Redis
{
    private $client;

    /**
     * __construct.
     *
     * @param string url
     * @param int port
     * @param string role
     *
     * @return void
     */
    public function __construct(string $url = '51.75.78.191', int $port = 6379, string $role = 'master')
    {
        $this->client = new Client('tcp://'.$url.':'.$port.'?role='.$role);
    }

    /**
     * set.
     *
     * @param string index
     * @param mixed value
     *
     * @return void
     */
    public function set(string $index, $value)
    {
        return $this->client->set($index, $value);
    }

    /**
     * get.
     *
     * @param string index
     *
     * @return void
     */
    public function get(string $index)
    {
        return $this->client->get($index);
    }

    /**
     * del.
     *
     * @param string index
     *
     * @return void
     */
    public function del(string $index)
    {
        return $this->client->del($index);
    }

    /**
     * expire.
     *
     * @param string index
     * @param int time
     *
     * @return void
     */
    public function expire(string $index, int $time)
    {
        return $this->client->expire($index, $time);
    }

    /**
     * lpush.
     *
     * @param string index
     * @param array value
     *
     * @return void
     */
    public function lpush(string $index, array $value)
    {
        return $this->client->lpush($index, $value);
    }

    /**
     * rpush.
     *
     * @param string index
     * @param array value
     *
     * @return void
     */
    public function rpush(string $index, array $value)
    {
        return $this->client->rpush($index, $value);
    }
}
