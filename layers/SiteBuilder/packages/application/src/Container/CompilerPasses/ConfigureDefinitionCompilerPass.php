<?php

declare(strict_types=1);

namespace PoP\Application\Container\CompilerPasses;

use PoP\ComponentModel\Modules\DefinitionGroups;
use PoP\Definitions\DefinitionManagerInterface;
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
                $this->createReference('emoji_definition_resolver'),
                DefinitionGroups::COMPONENTS
            ]
        );
    }
}
