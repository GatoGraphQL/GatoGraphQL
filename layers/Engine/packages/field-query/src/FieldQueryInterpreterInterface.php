<?php

declare(strict_types=1);

namespace PoP\FieldQuery;

use stdClass;

interface FieldQueryInterpreterInterface
{
    public function getFieldName(string $field): string;
    public function getFieldArgs(string $field): ?string;
    public function isFieldArgumentValueAField(mixed $fieldArgValue): bool;
    public function isFieldArgumentValueAVariable(mixed $fieldArgValue): bool;
    public function isFieldArgumentValueAnExpression(mixed $fieldArgValue): bool;
    public function isFieldArgumentValueDynamic(mixed $fieldArgValue): bool;
    public function getFieldAlias(string $field): ?string;
    /**
     * Return an array with the position where the alias starts (including the "@") and its length
     * Return null if the field has no alias
     *
     * @return array<string, int>|null
     */
    public function getFieldAliasPositionSpanInField(string $field): ?array;
    public function getFieldDirectives(string $field, bool $includeSyntaxDelimiters = false): ?string;
    public function getFieldDirectiveArgs(string $fieldDirective): ?string;
    /**
     * @param array<string|null> $directive
     */
    public function getDirectiveName(array $directive): string;
    /**
     * @param array<string, mixed> $directiveArgs
     * @return array<string|null>
     */
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
    public function wrapStringInQuotes(string $value): string;
}
