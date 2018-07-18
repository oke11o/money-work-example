<?php

namespace App\Kernel\Http;

class Response
{
    private $body;

    public function __construct(string $body)
    {
        $this->body = $body;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    public function send(): void
    {
        echo $this->getBody();
    }

}