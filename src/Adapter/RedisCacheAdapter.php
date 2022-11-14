<?php

namespace CaioMarcatti12\CacheManager\Adapter;

use CaioMarcatti12\CacheManager\Interfaces\CacheInterface;
use CaioMarcatti12\Core\Validation\Assert;
use CaioMarcatti12\Env\Objects\Property;
use Predis\Client;

class RedisCacheAdapter implements CacheInterface
{
    private Client $redis;

    public function __construct(){
        $host = Property::get('cache.redis.host', 'host.docker.internal');
        $port = Property::get('cache.redis.port', 6379);
        $password = Property::get('cache.redis.password', null);
        $sentinel = Property::get('cache.redis.sentinel', false);
        $replication = Property::get('cache.redis.replication', '');
        $service = Property::get('cache.redis.service', '');

        $options = [];

        if($sentinel){
            $options = ['replication' => $replication, 'service' => $service];
        }else{
            $options = ['cluster' => 'redis'];
        }

        $this->redis = new Client(["tcp://{$host}:{$port}"], $options);
        $this->redis->connect();

        if(Assert::isNotEmpty($password))
            $this->redis->auth($password);
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $value = $this->redis->get($key);

        if(Assert::isEmpty($value) || $value === false) return $default;

        return $value;
    }

    public function set(string $key, mixed $value = null, int $ttl = 0): void
    {
        if($ttl > 0){
            $this->redis->set($key, $value, 'EX', $ttl);
        }else{
            $this->redis->set($key, $value);
        }
    }

    public function del(string $key): void
    {
        $this->redis->del($key);
    }
}