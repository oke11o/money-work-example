<?php

namespace App\Kernel\Http;

use App\Kernel\ParameterBag;

class Request
{
    private $query;
    private $post;
    private $cookie;
    private $files;
    private $server;

    public function __construct($query, $post, $cookie, $files, $server)
    {
        $this->query = $query;
        $this->post = $post;
        $this->cookie = $cookie;
        $this->files = $files;
        $this->server = new ParameterBag($server);
    }

    /**
     * @return mixed
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @return mixed
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * @return mixed
     */
    public function getCookie()
    {
        return $this->cookie;
    }

    /**
     * @return mixed
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @return mixed
     */
    public function getServer()
    {
        return $this->server;
    }
}