<?php

declare(strict_types=1);

namespace PoP\Site\Container\CompilerPasses;

use PoP\Definitions\DefinitionManagerInterface;
use PoP\Resources\DefinitionGroups;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ConfigureDefinitionCompilerPass implements CompilerPassInterface
{
    /**
     * GraphQL persisted query for Introspection query
     */
    public function process(ContainerBuilder $containerBuilder): void
    {
        $definitionManagerDefinition = $containerBuilder->getDefinition(DefinitionManagerInterface::class);
        $definitionManagerDefinition->addMethodCall(
            'setDefinitionResolver',
            [
                new Reference('base36_definition_resolver'),
                DefinitionGroups::RESOURCES
            ]
        );
    }
}
