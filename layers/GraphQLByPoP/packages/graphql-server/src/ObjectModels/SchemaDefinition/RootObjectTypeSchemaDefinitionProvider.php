<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels\SchemaDefinition;

use GraphQLByPoP\GraphQLServer\Module;
use GraphQLByPoP\GraphQLServer\ModuleConfiguration;
use PoP\ComponentModel\App;
use PoPAPI\API\ObjectModels\SchemaDefinition\RootObjectTypeSchemaDefinitionProvider as UpstreamRootObjectTypeSchemaDefinitionProvider;

/**
 * The RootObject has the special role of also calculating the
 * global fields, connections and directives
 */
class RootObjectTypeSchemaDefinitionProvider extends UpstreamRootObjectTypeSchemaDefinitionProvider
{
    /**
     * Global fields are only added if enabled
     */
    protected function skipExposingGlobalFieldsInSchema(): bool
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return !$moduleConfiguration->exposeGlobalFieldsInGraphQLSchema();
    }
}
