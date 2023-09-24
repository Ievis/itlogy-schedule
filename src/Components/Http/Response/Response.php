<?php

namespace App\Components\Http\Response;

class Response
{
    public ?string $content;
    public int $status;
    public array $headers;

    public function __construct(string $content = '', int $status = 200, array $headers = [])
    {
        $this->content = $content;
        $this->status = $status;
        $this->headers = $headers;
    }

    public function setContent(string $content)
    {
        $this->content = $content;
    }

    public function setStatusCode(int $status)
    {
        $this->status = $status;
    }

    public function send()
    {
        foreach ($this->headers as $header_key => $header) {
            header($header_key . ':' . $header);
        }
        http_response_code($this->status);
        echo $this->content;

        fastcgi_finish_request();
        ob_end_flush();
    }
}