<?php

declare(strict_types=1);

namespace PoP\Application\Container\CompilerPasses;

use PoP\ComponentModel\Modules\DefinitionGroups;
use PoP\Definitions\DefinitionManagerInterface;
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
                new Reference('emoji_definition_resolver'),
                DefinitionGroups::MODULES
            ]
        );
    }
}
