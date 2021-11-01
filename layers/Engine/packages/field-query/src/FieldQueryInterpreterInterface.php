<?php

declare(strict_types=1);

namespace PoP\FieldQuery;

use stdClass;

interface FieldQueryInterpreterInterface
{
    public function getFieldName(string $field): string;
    public function getFieldArgs(string $field): ?string;
    public function isSkipOuputIfNullField(string $field): bool;
    public function removeSkipOuputIfNullFromField(string $field): string;
    public function isFieldArgumentValueAField(mixed $fieldArgValue): bool;
    public function isFieldArgumentValueAVariable(mixed $fieldArgValue): bool;
    public function isFieldArgumentValueAnExpression(mixed $fieldArgValue): bool;
    public function isFieldArgumentValueDynamic(mixed $fieldArgValue): bool;
    public function createFieldArgValueAsFieldFromFieldName(string $fieldName): string;
    public function getFieldAlias(string $field): ?string;
    /**
     * Return an array with the position where the alias starts (including the "@") and its length
     * Return null if the field has no alias
     *
     * @return array<string, int>|null
     */
    public function getFieldAliasPositionSpanInField(string $field): ?array;
    public function getFieldDirectives(string $field, bool $includeSyntaxDelimiters = false): ?string;
    /**
     * @return array<array<string|null>>
     */
    public function getDirectives(string $field): array;
    /**
     * @return array<array<string|null>>
     */
    public function extractFieldDirectives(string $fieldDirectives): array;
    /**
     * @param string[] $fieldDirectives
     */
    public function composeFieldDirectives(array $fieldDirectives): string;
    /**
     * @param array<string|null> $directive
     */
    public function convertDirectiveToFieldDirective(array $directive): string;
    /**
     * @return array<string|null>
     */
    public function listFieldDirective(string $fieldDirective): array;
    public function getFieldDirectiveName(string $fieldDirective): string;
    public function getFieldDirectiveArgs(string $fieldDirective): ?string;
    public function getFieldDirectiveNestedDirectives(
        string $fieldDirective,
        bool $includeSyntaxDelimiters = false
    ): ?string;
    /**
     * @param array<string, mixed> $directiveArgs
     */
    public function getFieldDirective(string $directiveName, array $directiveArgs): string;
    /**
     * @param array<string|null> $directive
     */
    public function getDirectiveName(array $directive): string;
    /**
     * @param array<string|null> $directive
     */
    public function getDirectiveArgs(array $directive): ?string;
    /**
     * @param array<string|null> $directive
     */
    public function getDirectiveNestedDirectives(array $directive): ?string;
    public function getFieldOutputKey(string $field): string;
    public function getDirectiveOutputKey(string $fieldDirective): string;
    /**
     * @return array<string|null>
     */
    public function listField(string $field): array;
    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<array<string|null>>|null $fieldDirectives
     */
    public function getField(
        string $fieldName,
        array $fieldArgs,
        ?string $fieldAlias = null,
        bool $skipOutputIfNull = false,
        ?array $fieldDirectives = [],
        bool $addFieldArgSymbolsIfEmpty = false
    ): string;
    public function composeField(
        string $fieldName,
        ?string $fieldArgs = '',
        ?string $fieldAlias = '',
        ?string $skipOutputIfNull = '',
        ?string $fieldDirectives = ''
    ): string;
    /**
     * @return array<string|null>
     */
    public function composeDirective(
        string $directiveName,
        ?string $directiveArgs = '',
        ?string $directiveNestedDirectives = ''
    ): array;
    /**
     * @param array<string, mixed> $directiveArgs
     * @return array<string|null>
     */
    public function getDirective(
        string $directiveName,
        array $directiveArgs,
        ?string $directiveNestedDirectives = ''
    ): array;
    public function composeFieldDirective(
        string $directiveName,
        ?string $directiveArgs = '',
        ?string $directiveNestedDirectives = ''
    ): string;
    /**
     * @param array<array<string|null>> $fieldDirectives
     */
    public function getFieldDirectivesAsString(array $fieldDirectives): string;
    /**
     * @return array<string, mixed>
     */
    public function getVariablesFromRequest(): array;
    /**
     * @param array<string, mixed> $fieldArgValue
     */
    public function getArrayAsStringForQuery(array $fieldArgValue): string;
    public function getObjectAsStringForQuery(stdClass $fieldArgValue): string;
    /**
     * @param array<string, mixed> $fieldArgs
     */
    public function getFieldArgsAsString(
        array $fieldArgs,
        bool $addFieldArgSymbolsIfEmpty = false
    ): string;
    /**
     * @param array<string, mixed> $directiveArgs
     */
    public function getDirectiveArgsAsString(array $directiveArgs): string;
    public function wrapStringInQuotes(string $value): string;
}
