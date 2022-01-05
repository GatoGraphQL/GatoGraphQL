<?php

declare(strict_types=1);

namespace PoP\Root\Container;

class SystemContainerBuilderFactory
{
    use ContainerBuilderFactoryTrait;

    public function getContainerClass(): string
    {
        return 'SystemServiceContainer';
    }
}
