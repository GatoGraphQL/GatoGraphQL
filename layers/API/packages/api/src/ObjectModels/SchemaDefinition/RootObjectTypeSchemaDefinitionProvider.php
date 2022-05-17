<?php

declare(strict_types=1);

namespace PoPAPI\API\ObjectModels\SchemaDefinition;

use PoP\Root\App;
use PoPAPI\API\Module;
use PoPAPI\API\ComponentConfiguration;
use PoPAPI\API\Schema\SchemaDefinition;

/**
 * The RootObject has the special role of also calculating the
 * global fields, connections and directives
 */
class RootObjectTypeSchemaDefinitionProvider extends ObjectTypeSchemaDefinitionProvider
{
    public function getSchemaDefinition(): array
    {
        $schemaDefinition = parent::getSchemaDefinition();

        // The global directives are added always, since those are the "normal" directives in GraphQL
        $globalSchemaDefinition = [];
        $this->addDirectiveSchemaDefinitions($globalSchemaDefinition, true);
        $schemaDefinition[SchemaDefinition::GLOBAL_DIRECTIVES] = $globalSchemaDefinition[SchemaDefinition::DIRECTIVES];

        // Global fields are only added if enabled
        if ($this->skipExposingGlobalFieldsInSchema()) {
            return $schemaDefinition;
        }

        $this->addFieldSchemaDefinitions($globalSchemaDefinition, true);
        return array_merge(
            $schemaDefinition,
            [
                SchemaDefinition::GLOBAL_FIELDS => $globalSchemaDefinition[SchemaDefinition::FIELDS],
            ]
        );
    }

    /**
     * Global fields are only added if enabled
     */
    protected function skipExposingGlobalFieldsInSchema(): bool
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Module::class)->getConfiguration();
        return $componentConfiguration->skipExposingGlobalFieldsInFullSchema();
    }
}
