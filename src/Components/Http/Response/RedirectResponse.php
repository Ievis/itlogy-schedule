<?php

namespace App\Components\Http\Response;

class RedirectResponse extends Response
{
    public string $url;
    public int $status;
    public array $headers;

    public function __construct(string $url, int $status = 302, array $headers = [])
    {
        parent::__construct();

        $this->url = $url;
        $this->status = $status;
        $this->headers = empty($headers)
            ? ['Location' => $url]
            : $headers;
    }
}