<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Container\CompilerPasses;

use PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups;
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
        $enableSchemaEntityRegistries = ComponentConfiguration::enableSchemaEntityRegistries();
        $directiveRegistryDefinition = $enableSchemaEntityRegistries ?
            $containerBuilder->getDefinition(DirectiveRegistryInterface::class)
            : null;
        $definitions = $containerBuilder->getDefinitions();
        foreach ($definitions as $definition) {
            $definitionClass = $definition->getClass();
            if ($definitionClass === null || !is_a($definitionClass, DirectiveResolverInterface::class, true)) {
                continue;
            }

            $definitionClass::attach(AttachableExtensionGroups::DIRECTIVERESOLVERS);

            // Register the directive in the registry
            if ($enableSchemaEntityRegistries) {
                $directiveRegistryDefinition->addMethodCall('addDirectiveResolverClass', [$definitionClass]);
            }
        }
    }
}
