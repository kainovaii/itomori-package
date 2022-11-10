<?php

namespace Obsidian\Core;

class Session
{
    private $client;

    public function __construct()
    {
        $this->client = new Redis();
    }

    /**
     * set.
     *
     * @param array options
     *
     * @return void
     */
    public function set(array $options)
    {
        return $this->client->set('user::'.$options['ip'], json_encode($options));
    }

    /**
     * get.
     *
     * @param string ip
     *
     * @return void
     */
    public function get(string $ip)
    {
        return json_decode($this->client->get('user::'.$ip));
    }

    public function unset(string $ip)
    {
        return $this->client->del('user::'.$ip);
    }
}
