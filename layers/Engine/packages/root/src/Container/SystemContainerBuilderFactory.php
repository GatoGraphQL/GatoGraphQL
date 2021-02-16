<?php

declare(strict_types=1);

namespace PoP\Root\Container;

class SystemContainerBuilderFactory
{
    use ContainerBuilderFactoryTrait;

    public static function getContainerClass(): string
    {
        return 'SystemServiceContainer';
    }
}
