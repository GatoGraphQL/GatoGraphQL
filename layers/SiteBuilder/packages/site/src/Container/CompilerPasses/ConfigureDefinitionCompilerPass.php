<?php

declare(strict_types=1);

namespace PoP\Site\Container\CompilerPasses;

use PoP\Definitions\DefinitionManagerInterface;
use PoP\Resources\DefinitionGroups;
use PoP\Root\Container\CompilerPasses\AbstractCompilerPass;
use PoP\Root\Container\ContainerBuilderWrapperInterface;

class ConfigureDefinitionCompilerPass extends AbstractCompilerPass
{
    /**
     * GraphQL persisted query for Introspection query
     */
    protected function doProcess(ContainerBuilderWrapperInterface $containerBuilderWrapper): void
    {
        $definitionManagerDefinition = $containerBuilderWrapper->getDefinition(DefinitionManagerInterface::class);
        $definitionManagerDefinition->addMethodCall(
            'setDefinitionResolver',
            [
                $this->createReference('base36_definition_resolver'),
                DefinitionGroups::RESOURCES
            ]
        );
    }
}
