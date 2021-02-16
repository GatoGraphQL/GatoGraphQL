<?php

declare(strict_types=1);

namespace PoP\Root\Container;

class ContainerBuilderFactory extends AbstractContainerBuilderFactory
{
    public static function getContainerClass(): string
    {
        return 'ProjectServiceContainer';
    }
}
