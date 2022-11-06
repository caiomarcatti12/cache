<?php

namespace CaioMarcatti12\CacheManager\Adapter;

use CaioMarcatti12\CacheManager\Interfaces\CacheInterface;
use CaioMarcatti12\Core\Validation\Assert;

class MemoryCacheAdapter implements CacheInterface
{
    private array $database = [];

    public function __construct(){

    }

    public function get(string $key, mixed $default = null): mixed
    {
        $value = $this->database[$key] ?? null;

        if(Assert::isEmpty($value) || $value === false) return $default;

        return $value;
    }

    public function set(string $key, mixed $value = null, int $ttl = 0): void
    {
        $this->database[$key] =  $value;
    }

    public function del(string $key): void
    {
        unset($this->database[$key]);
    }
}