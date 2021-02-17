<?php

declare(strict_types=1);

namespace PoP\Application\Container\CompilerPasses;

use PoP\Definitions\DefinitionManagerInterface;
use PoP\ComponentModel\Modules\DefinitionGroups;
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
                new Reference('emoji_definition_resolver'),
                DefinitionGroups::MODULES
            ]
        );
    }
}
