<?php

namespace CaioMarcatti12\CacheManager\Annotation;

use Attribute;
use CaioMarcatti12\CacheManager\Adapter\RedisCacheAdapter;
use CaioMarcatti12\Core\Modules\Modules;
use CaioMarcatti12\Core\Modules\ModulesEnum;

#[Attribute(Attribute::TARGET_CLASS)]
class EnableCache
{
    private string $adapter = '';

    public function __construct(string $adapter = RedisCacheAdapter::class)
    {
        $this->adapter = $adapter;

        Modules::enable(ModulesEnum::CACHE);
    }

    public function getAdapter(): string
    {
        return $this->adapter;
    }
}