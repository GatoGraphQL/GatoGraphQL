<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Schema;

use PoP\ComponentModel\Feedback\SchemaInputValidationFeedbackStore;
use PoP\GraphQLParser\Spec\Parser\Ast\WithArgumentsInterface;

interface SchemaCastingServiceInterface
{
    /**
     * @param array<string,array<string,mixed>> $argumentSchemaDefinition
     */
    public function castArguments(
        WithArgumentsInterface $withArgumentsAST,
        array $argumentSchemaDefinition,
        SchemaInputValidationFeedbackStore $schemaInputValidationFeedbackStore,
    ): void;
}
