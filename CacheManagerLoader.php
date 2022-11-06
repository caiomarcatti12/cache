<?php

namespace CaioMarcatti12\Event;

use CaioMarcatti12\CacheManager\Adapter\RedisCacheAdapter;
use CaioMarcatti12\CacheManager\Interfaces\CacheInterface;
use CaioMarcatti12\Core\Bean\Objects\BeanProxy;
use CaioMarcatti12\Core\Launcher\Annotation\Launcher;
use CaioMarcatti12\Core\Launcher\Enum\LauncherPriorityEnum;
use CaioMarcatti12\Core\Launcher\Interfaces\LauncherInterface;

#[Launcher(LauncherPriorityEnum::BEFORE_LOAD_APPLICATION)]
class CacheManagerLoader implements LauncherInterface
{
    public function handler(): void
    {
        BeanProxy::add(CacheInterface::class, RedisCacheAdapter::class);
    }
}
