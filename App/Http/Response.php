<?php


namespace App\Http;


class Response
{
    /**
     * @var array
     */
    private $headers = [];

    /**
     * @var
     */
    private $body;

    /**
     * @param array $headers
     */
    public function setHeaders(array $headers): void
    {
        $this->headers = $headers;
    }

    /**
     * @param mixed $body
     */
    public function setBody($body): void
    {
        $this->body = $body;
    }



    public function send() {
        foreach ($this->headers as $header => $value) {
            header('$header: $value');
        }

        if (!is_null($this->body)) {
            echo (string) $this->body;
        }
    }

    public function redirect(string $url) {
        $this->setHeaders('Location', $url);
    }

}