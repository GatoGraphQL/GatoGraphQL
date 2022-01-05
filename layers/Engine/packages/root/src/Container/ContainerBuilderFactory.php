<?php

declare(strict_types=1);

namespace PoP\Root\Container;

class ContainerBuilderFactory
{
    use ContainerBuilderFactoryTrait;

    public function getContainerClass(): string
    {
        return 'ApplicationServiceContainer';
    }
}
