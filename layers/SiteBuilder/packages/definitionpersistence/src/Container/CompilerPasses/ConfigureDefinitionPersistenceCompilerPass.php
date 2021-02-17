<?php

declare(strict_types=1);

namespace PoP\DefinitionPersistence\Container\CompilerPasses;

use PoP\Definitions\DefinitionManagerInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ConfigureDefinitionPersistenceCompilerPass implements CompilerPassInterface
{
    /**
     * GraphQL persisted query for Introspection query
     */
    public function process(ContainerBuilder $containerBuilder): void
    {
        $definitionManagerDefinition = $containerBuilder->getDefinition(DefinitionManagerInterface::class);
        $definitionManagerDefinition->addMethodCall(
            'setDefinitionPersistence',
            [
                new Reference('file_definition_persistence')
            ]
        );
    }
}
