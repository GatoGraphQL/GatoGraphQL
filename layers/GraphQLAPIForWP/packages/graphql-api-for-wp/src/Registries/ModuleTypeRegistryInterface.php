<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Registries;

use InvalidArgumentException;
use GraphQLAPI\GraphQLAPI\ModuleTypeResolvers\ModuleTypeResolverInterface;

interface ModuleTypeRegistryInterface
{
    public function addModuleTypeResolver(ModuleTypeResolverInterface $moduleTypeResolver): void;
    /**
     * @throws InvalidArgumentException If module does not exist
     */
    public function getModuleTypeResolver(string $moduleType): ModuleTypeResolverInterface;
}
