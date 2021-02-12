<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Container\CompilerPasses;

use PoP\ComponentModel\ComponentConfiguration;
use PoP\ComponentModel\FieldInterfaceResolvers\FieldInterfaceResolverInterface;
use PoP\ComponentModel\Registries\FieldInterfaceRegistryInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RegisterFieldInterfaceResolverCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $containerBuilder): void
    {
        /**
         * Check the registries are enabled
         */
        if (!ComponentConfiguration::enableSchemaEntityRegistries()) {
            return;
        }
        $fieldInterfaceRegistryDefinition = $containerBuilder->getDefinition(FieldInterfaceRegistryInterface::class);
        $definitions = $containerBuilder->getDefinitions();
        foreach ($definitions as $definition) {
            $definitionClass = $definition->getClass();
            if ($definitionClass === null || !is_a($definitionClass, FieldInterfaceResolverInterface::class, true)) {
                continue;
            }

            // Register the directive in the registry
            $fieldInterfaceRegistryDefinition->addMethodCall(
                'addFieldInterfaceResolverClass',
                [$definitionClass]
            );
        }
    }
}
