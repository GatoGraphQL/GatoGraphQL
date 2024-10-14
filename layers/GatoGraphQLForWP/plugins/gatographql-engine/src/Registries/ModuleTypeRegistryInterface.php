<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Registries;

use GatoGraphQL\GatoGraphQL\Exception\ModuleTypeNotExistsException;
use GatoGraphQL\GatoGraphQL\Services\ModuleTypeResolvers\ModuleTypeResolverInterface;

interface ModuleTypeRegistryInterface
{
    public function addModuleTypeResolver(ModuleTypeResolverInterface $moduleTypeResolver): void;
    /**
     * @throws ModuleTypeNotExistsException If module does not exist
     */
    public function getModuleTypeResolver(string $moduleType): ModuleTypeResolverInterface;
}
