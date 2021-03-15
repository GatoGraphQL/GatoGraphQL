<?php

declare(strict_types=1);

namespace PoP\Site\Container\CompilerPasses;

use PoP\Definitions\DefinitionManagerInterface;
use PoP\Resources\DefinitionGroups;
use PoP\Root\Container\CompilerPasses\AbstractCompilerPass;
use PoP\Root\Container\CompilerPassContainerInterface;
use Symfony\Component\DependencyInjection\Reference;

class ConfigureDefinitionCompilerPass extends AbstractCompilerPass
{
    /**
     * GraphQL persisted query for Introspection query
     */
    protected function doProcess(CompilerPassContainerInterface $containerBuilder): void
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
