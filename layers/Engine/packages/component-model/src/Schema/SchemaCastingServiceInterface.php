<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Schema;

use PoP\ComponentModel\Feedback\SchemaInputValidationFeedbackStore;
use PoP\GraphQLParser\Spec\Parser\Ast\WithArgumentsInterface;

interface SchemaCastingServiceInterface
{
    /**
     * @param array<string,mixed> $argumentKeyValues
     * @param array<string,array<string,mixed>> $argumentSchemaDefinition
     * @return array<string,mixed>
     */
    public function castArguments(
        array $argumentKeyValues,
        array $argumentSchemaDefinition,
        WithArgumentsInterface $withArgumentsAST,
        SchemaInputValidationFeedbackStore $schemaInputValidationFeedbackStore,
    ): array;
}
