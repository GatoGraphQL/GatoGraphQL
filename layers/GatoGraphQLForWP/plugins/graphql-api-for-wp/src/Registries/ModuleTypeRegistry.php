<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Registries;

use GatoGraphQL\GatoGraphQL\Exception\ModuleTypeNotExistsException;
use GatoGraphQL\GatoGraphQL\Services\ModuleTypeResolvers\ModuleTypeResolverInterface;

class ModuleTypeRegistry implements ModuleTypeRegistryInterface
{
    /**
     * @var array<string,ModuleTypeResolverInterface>
     */
    protected array $moduleTypeResolvers = [];

    public function addModuleTypeResolver(ModuleTypeResolverInterface $moduleTypeResolver): void
    {
        foreach ($moduleTypeResolver->getModuleTypesToResolve() as $moduleType) {
            $this->moduleTypeResolvers[$moduleType] = $moduleTypeResolver;
        }
    }

    /**
     * @throws ModuleTypeNotExistsException If module does not exist
     */
    public function getModuleTypeResolver(string $moduleType): ModuleTypeResolverInterface
    {
        if (!isset($this->moduleTypeResolvers[$moduleType])) {
            throw new ModuleTypeNotExistsException(sprintf(
                \__('Module type \'%s\' does not exist', 'graphql-api'),
                $moduleType
            ));
        }
        return $this->moduleTypeResolvers[$moduleType];
    }
}
