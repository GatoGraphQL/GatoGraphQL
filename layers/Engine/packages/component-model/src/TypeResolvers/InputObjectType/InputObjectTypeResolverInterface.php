<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\DeprecatableInputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use stdClass;

/**
 * Based on GraphQL InputObject Type
 *
 * @see https://spec.graphql.org/draft/#sec-Input-Objects
 */
interface InputObjectTypeResolverInterface extends DeprecatableInputTypeResolverInterface
{
    /**
     * Define input fields
     *
     * @return array<string, InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers(): array;
    /**
     * @return string[]
     */
    public function getAdminInputFieldNames(): array;
    public function getInputFieldDescription(string $inputFieldName): ?string;
    public function getInputFieldDefaultValue(string $inputFieldName): mixed;
    public function getInputFieldTypeModifiers(string $inputFieldName): int;
    /**
     * Consolidation of the schema inputs. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     *
     * @return array<string, InputTypeResolverInterface>
     */
    public function getConsolidatedInputFieldNameTypeResolvers(): array;
    /**
     * @return string[]
     */
    public function getConsolidatedAdminInputFieldNames(): array;
    public function getConsolidatedInputFieldDescription(string $inputFieldName): ?string;
    public function getConsolidatedInputFieldDefaultValue(string $inputFieldName): mixed;
    public function getConsolidatedInputFieldTypeModifiers(string $inputFieldName): int;

    /**
     * Input fields may not be directly visible in the schema,
     * eg: because they are used only by the application, and must not
     * be exposed to the user
     */
    public function skipExposingInputFieldInSchema(string $inputFieldName): bool;
    public function getInputFieldSchemaDefinition(string $inputFieldName): array;
    /**
     * Validate constraints on the input's value
     *
     * @return string[] Error messages
     */
    public function validateInputValue(stdClass $inputValue): array;
    public function hasMandatoryInputFields(): bool;
}
