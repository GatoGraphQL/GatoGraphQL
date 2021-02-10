<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Container\CompilerPasses;

use PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups;
use PoP\ComponentModel\AttachableExtensions\AttachExtensionServiceInterface;
use PoP\ComponentModel\FieldResolvers\FieldResolverInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AttachFieldResolverCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $containerBuilder): void
    {
        $attachExtensionServiceDefinition = $containerBuilder->getDefinition(AttachExtensionServiceInterface::class);
        $definitions = $containerBuilder->getDefinitions();
        foreach ($definitions as $definition) {
            $definitionClass = $definition->getClass();
            if ($definitionClass === null || !is_a($definitionClass, FieldResolverInterface::class, true)) {
                continue;
            }

            $attachExtensionServiceDefinition->addMethodCall('enqueueExtension', [$definitionClass, AttachableExtensionGroups::FIELDRESOLVERS]);
        }
    }
}
