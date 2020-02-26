<?php


namespace App\Service;

class RequestService
{
    public function __construct()
    {
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

    public function redirect(string $path) {
        header('Location: ' . $path);
    }

    private function getRawFromPost(string $key, $default = null) {
        return $_POST[$key] ?? $default;
    }

    private function getRawFromGet(string $key, $default = null) {
        return $_GET[$key] ?? $default;
    }



}