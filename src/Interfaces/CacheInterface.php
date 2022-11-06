<?php

namespace CaioMarcatti12\CacheManager\Interfaces;

interface CacheInterface
{
    public function get(string $key, mixed $default = null): mixed;
    public function set(string $key, mixed $value = null, int $ttl = 0): void;
    public function del(string $key): void;
}