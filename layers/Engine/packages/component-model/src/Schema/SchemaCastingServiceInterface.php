<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Schema;

use PoP\ComponentModel\Feedback\SchemaInputValidationFeedbackStore;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;

interface SchemaCastingServiceInterface
{
    /**
     * @param Argument[] $arguments
     * @param array<string,array<string,mixed>> $argumentSchemaDefinition
     * @param SchemaInputValidationFeedbackStore $schemaInputValidationFeedbackStore
     * @return void
     */
    public function castArguments(
        array $arguments,
        array $argumentSchemaDefinition,
        SchemaInputValidationFeedbackStore $schemaInputValidationFeedbackStore,
    ): void;
}
