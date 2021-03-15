<?php

declare(strict_types=1);

namespace PoP\DefinitionPersistence\Container\CompilerPasses;

use PoP\Definitions\DefinitionManagerInterface;
use PoP\Root\Container\CompilerPasses\AbstractCompilerPass;
use PoP\Root\Container\ContainerBuilderWrapperInterface;

class ConfigureDefinitionPersistenceCompilerPass extends AbstractCompilerPass
{
    /**
     * GraphQL persisted query for Introspection query
     */
    protected function doProcess(ContainerBuilderWrapperInterface $containerBuilderWrapper): void
    {
        $definitionManagerDefinition = $containerBuilderWrapper->getDefinition(DefinitionManagerInterface::class);
        $definitionManagerDefinition->addMethodCall(
            'setDefinitionPersistence',
            [
                $this->createReference('file_definition_persistence')
            ]
        );
    }
}
