<?php

declare(strict_types=1);

namespace PoP\API\ObjectModels\SchemaDefinition;

use PoP\API\ComponentConfiguration;
use PoP\API\Schema\SchemaDefinition;

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
        if (
            $directives = array_merge(
                $schemaDefinition[SchemaDefinition::DIRECTIVES] ?? [],
                $globalSchemaDefinition[SchemaDefinition::DIRECTIVES] ?? []
            )
        ) {
            $schemaDefinition[SchemaDefinition::DIRECTIVES] = $directives;
        }

        // Global fields are only added if enabled
        if (ComponentConfiguration::skipExposingGlobalFieldsInFullSchema()) {
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
}
