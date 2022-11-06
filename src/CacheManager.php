<?php

namespace CaioMarcatti12\CacheManager;


use CaioMarcatti12\CacheManager\Interfaces\CacheInterface;
use CaioMarcatti12\Core\Factory\Annotation\Autowired;
use CaioMarcatti12\Core\Validation\Assert;
use CaioMarcatti12\Data\ObjectMapper;
use CaioMarcatti12\Env\Annotation\Value;

class CacheManager implements CacheInterface
{
    #[Autowired]
    private CacheInterface $cache;

    #[Value('application.name')]
    private string $applicationName;

    public function get(string $key, mixed $default = null): mixed
    {
        $key = $this->makeKey($key);
        $value = $this->cache->get($key, $default);

        if(Assert::isEmpty($value) || $value === false) return $default;

        $object = json_decode($value, true);

        if(Assert::isPrimitiveTypeName($object['type'])){
             settype($object['value'], $object['type']);
             return $object['value'];
        }

        return ObjectMapper::mapper($object['value'], $object['type']);
    }

    public function set(string $key, mixed $value = null, int $ttl = -1): void
    {
        $key = $this->makeKey($key);
        $type = gettype($value);

        if(!Assert::isPrimitiveTypeName($type)){
            $type = get_class($value);
            $value = ObjectMapper::toArray($value);
        }

        $object = [];
        $object['type'] = $type;
        $object['value'] = $value;

        $this->cache->set($key, json_encode($object), $ttl);
    }

    public function del(string $key): void
    {
        $key = $this->makeKey($key);
        $this->cache->del($key);
    }

    private function makeKey(string $key): string{
        $key = $this->applicationName.'-'.$key;
        $key = str_replace(" ","_",preg_replace("/&([a-z])[a-z]+;/i", "$1", htmlentities(trim($key))));

        return $key;
    }
}