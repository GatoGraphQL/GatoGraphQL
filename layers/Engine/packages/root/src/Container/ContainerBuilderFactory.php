<?php

declare(strict_types=1);

namespace PoP\Root\Container;

class ContainerBuilderFactory extends AbstractContainerBuilderFactory
{
    protected static function getContainerClass(): string
    {
        return 'ProjectServiceContainer';
    }
}
