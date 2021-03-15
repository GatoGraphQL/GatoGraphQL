<?php

declare(strict_types=1);

namespace PoP\Root\Container;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

interface CompilerPassContainerInterface
{
    public function getContainerBuilder(): ContainerBuilder;
    public function getDefinition(string $id): Definition;
}
