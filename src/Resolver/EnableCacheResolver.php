<?php

namespace CaioMarcatti12\CacheManager\Resolver;

use CaioMarcatti12\CacheManager\Annotation\EnableCache;
use CaioMarcatti12\CacheManager\Interfaces\CacheInterface;
use ReflectionClass;
use CaioMarcatti12\Core\Bean\Annotation\AnnotationResolver;
use CaioMarcatti12\Core\Bean\Interfaces\ClassResolverInterface;
use CaioMarcatti12\Core\Bean\Objects\BeanProxy;

#[AnnotationResolver(EnableCache::class)]
class EnableCacheResolver  implements ClassResolverInterface
{
    public function handler(object &$instance): void
    {
        $reflectionClass = new ReflectionClass($instance);

        $attributes = $reflectionClass->getAttributes(EnableCache::class);

        /** @var EnableCache $attribute */
        $attribute = ($attributes[0]->newInstance());

        BeanProxy::add(CacheInterface::class,  $attribute->getAdapter());
    }
}