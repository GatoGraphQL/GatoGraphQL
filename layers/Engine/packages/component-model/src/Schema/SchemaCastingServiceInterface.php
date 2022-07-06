<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Schema;

use PoP\ComponentModel\Feedback\SchemaInputValidationFeedbackStore;

interface SchemaCastingServiceInterface
{
    /**
     * @param array<string,mixed> $fieldData
     * @param array<string,array<string,mixed>> $argumentSchemaDefinition
     */
    public function castArguments(
        array &$fieldData,
        array $argumentSchemaDefinition,
        SchemaInputValidationFeedbackStore $schemaInputValidationFeedbackStore,
    ): void;
}
