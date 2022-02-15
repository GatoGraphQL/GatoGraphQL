<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Registries;

use GraphQLAPI\GraphQLAPI\Exception\PluginBackendException;
use GraphQLAPI\GraphQLAPI\Services\ModuleTypeResolvers\ModuleTypeResolverInterface;

interface ModuleTypeRegistryInterface
{
    public function addModuleTypeResolver(ModuleTypeResolverInterface $moduleTypeResolver): void;
    /**
     * @throws PluginBackendException If module does not exist
     */
    public function getModuleTypeResolver(string $moduleType): ModuleTypeResolverInterface;
}
