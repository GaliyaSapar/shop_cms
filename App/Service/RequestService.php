<?php


namespace App\Service;


class RequestService
{
    private function __construct()
    {
    }

    public static function getIntFromGet(string $key, $default = 0) {
        return (int) static::getRawFromGet($key, $default); //разница обращения self static
    }

    public static function getStringFromGet(string $key, $default = '') {
        return (string) static::getRawFromGet($key, $default);
    }

    public static function getFloatFromGet(string $key, $default = 0) {
        return (float) static::getRawFromGet($key, $default);
    }

    public static function getArrayFromGet(string $key, $default = []) {
        return (array) static::getRawFromGet($key, $default);
    }

    public static function getIntFromPost(string $key, $default = 0) {
        return (int) static::getRawFromPost($key, $default);;
    }

    public static function getStringFromPost(string $key, $default = '') {
        return (string) static::getRawFromPost($key, $default);
    }

    public static function getFloatFromPost(string $key, $default = 0) {
        return (float) static::getRawFromPost($key, $default);
    }

    public static function getArrayFromPost(string $key, $default = []) {
        return (array) static::getRawFromPost($key, $default);
    }

    public static function redirect(string $path) {
        header('Location: ' . $path);
    }

    private static function getRawFromPost(string $key, $default = null) {
        return $_POST[$key] ?? $default;
    }

    private static function getRawFromGet(string $key, $default = null) {
        return $_GET[$key] ?? $default;
    }



}