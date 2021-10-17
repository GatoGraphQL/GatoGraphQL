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
        $globalSchemaDefinition = $this->getObjectTypeSchemaDefinition(true);
        $schemaDefinition[SchemaDefinition::DIRECTIVES] = array_merge(
            $schemaDefinition[SchemaDefinition::DIRECTIVES],
            $globalSchemaDefinition[SchemaDefinition::DIRECTIVES]
        );
        if (ComponentConfiguration::exposeGlobalFieldsInSchema()) {
            return array_merge(
                $schemaDefinition,
                [
                    SchemaDefinition::GLOBAL_FIELDS => $globalSchemaDefinition[SchemaDefinition::FIELDS],
                    SchemaDefinition::GLOBAL_CONNECTIONS => $globalSchemaDefinition[SchemaDefinition::CONNECTIONS],
                ]
            );
        }
        return $schemaDefinition;
    }
}
