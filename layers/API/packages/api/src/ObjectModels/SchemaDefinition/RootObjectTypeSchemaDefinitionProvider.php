<?php

declare(strict_types=1);

namespace PoP\API\ObjectModels\SchemaDefinition;

use PoP\API\Schema\SchemaDefinition;

/**
 * The RootObject has the special role of also calculating the
 * global fields, connections and directives
 */
class RootObjectTypeSchemaDefinitionProvider extends ObjectTypeSchemaDefinitionProvider
{
    public function getSchemaDefinition(): array
    {
        $globalSchemaDefinition = $this->getObjectTypeSchemaDefinition(true);
        return array_merge(
            parent::getSchemaDefinition(),
            [
                SchemaDefinition::GLOBAL_DIRECTIVES => $globalSchemaDefinition[SchemaDefinition::DIRECTIVES],
                SchemaDefinition::GLOBAL_FIELDS => $globalSchemaDefinition[SchemaDefinition::FIELDS],
                SchemaDefinition::GLOBAL_CONNECTIONS => $globalSchemaDefinition[SchemaDefinition::CONNECTIONS],
            ]
        );
    }
}
