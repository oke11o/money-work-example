<?php

namespace App\Kernel\Http;

use Fig\Http\Message\StatusCodeInterface;

class RedirectResponse extends Response
{
    /**
     * @var string
     */
    private $url;
    /**
     * @var string
     */
    private $code;

    public function __construct(string $url, string $code = StatusCodeInterface::STATUS_MOVED_PERMANENTLY)
    {
        parent::__construct('');

        $this->url = $url;
        $this->code = $code;
    }

    public function send(): void
    {
        header('Location: '.$this->url, true, $this->code);
    }
}