<?php

declare(strict_types=1);

namespace PoP\DefinitionPersistence\Container\CompilerPasses;

use PoP\Definitions\DefinitionManagerInterface;
use PoP\Root\Container\CompilerPasses\AbstractCompilerPass;
use PoP\Root\Container\CompilerPassContainerInterface;
use Symfony\Component\DependencyInjection\Reference;

class ConfigureDefinitionPersistenceCompilerPass extends AbstractCompilerPass
{
    /**
     * GraphQL persisted query for Introspection query
     */
    protected function doProcess(CompilerPassContainerInterface $containerBuilder): void
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
