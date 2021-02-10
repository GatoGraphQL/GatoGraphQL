<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Container\CompilerPasses;

use PoP\ComponentModel\ComponentConfiguration;
use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\Registries\DirectiveRegistryInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AttachAndRegisterDirectiveResolverCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $containerBuilder): void
    {
        /**
         * Check the registries are enabled
         */
        if (!ComponentConfiguration::enableSchemaEntityRegistries()) {
            return;
        }
        $directiveRegistryDefinition = $containerBuilder->getDefinition(DirectiveRegistryInterface::class);
        $definitions = $containerBuilder->getDefinitions();
        foreach ($definitions as $definition) {
            $definitionClass = $definition->getClass();
            if ($definitionClass === null || !is_a($definitionClass, DirectiveResolverInterface::class, true)) {
                continue;
            }

            // Register the directive in the registry
            $directiveRegistryDefinition->addMethodCall(
                'addDirectiveResolverClass',
                [$definitionClass]
            );
        }
    }
}
