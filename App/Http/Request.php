<?php


namespace App\Http;


class Request
{
    /**
     * @var string
     */
    private $url;

    public function __construct()
    {
        $this->parseUrl();
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }


    public function getIntFromGet(string $key, $default = 0) {
        return (int) $this->getRawFromGet($key, $default); //разница обращения self static
    }

    public function getStringFromGet(string $key, $default = '') {
        return (string) $this->getRawFromGet($key, $default);
    }

    public function getFloatFromGet(string $key, $default = 0) {
        return (float) $this->getRawFromGet($key, $default);
    }

    public function getArrayFromGet(string $key, $default = []) {
        return (array) $this->getRawFromGet($key, $default);
    }

    public function getIntFromPost(string $key, $default = 0) {
        return (int) $this->getRawFromPost($key, $default);;
    }

    public function getStringFromPost(string $key, $default = '') {
        return (string) $this->getRawFromPost($key, $default);
    }

    public function getFloatFromPost(string $key, $default = 0) {
        return (float) $this->getRawFromPost($key, $default);
    }

    public function getArrayFromPost(string $key, $default = []) {
        return (array) $this->getRawFromPost($key, $default);
    }

    private function getRawFromPost(string $key, $default = null) {
        return $_POST[$key] ?? $default;
    }

    private function getRawFromGet(string $key, $default = null) {
        return $_GET[$key] ?? $default;
    }

    private function parseUrl() {
        $request_uri = $_SERVER['REQUEST_URI'];

        $request_data = explode('?', $request_uri);
        $this->url = $request_data[0];
    }
}