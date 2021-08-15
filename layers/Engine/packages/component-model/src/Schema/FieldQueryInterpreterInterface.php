<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Schema;

use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;

interface FieldQueryInterpreterInterface extends \PoP\FieldQuery\FieldQueryInterpreterInterface
{
    /**
     * If two different fields for the same type have the same fieldOutputKey, then
     * add a counter to the second one, so each of them is unique.
     * That is to avoid overriding the previous value, as when doing:
     *
     *   ?query=posts.title|self.excerpt@title
     *
     * In this case, the value of the excerpt would override the value of the title,
     * since they both have fieldOutputKey "title"
     */
    public function getUniqueFieldOutputKey(TypeResolverInterface $typeResolver, string $field): string;
    public function getUniqueFieldOutputKeyByTypeResolverClass(string $typeResolverClass, string $field): string;
    public function getUniqueFieldOutputKeyByTypeOutputName(string $dbKey, string $field): string;
    /**
     * Extract field args without using the schema. It is needed to find out which fieldResolver will process a field, where we can't depend on the schema since this one needs to know who the fieldResolver is, creating an infitine loop
     */
    public function extractStaticFieldArguments(string $field, ?array $variables = null): array;
    public function extractStaticDirectiveArguments(string $directive, ?array $variables = null): array;
    public function extractFieldArguments(
        TypeResolverInterface $typeResolver,
        string $field,
        ?array $variables = null,
        ?array &$schemaErrors = null,
        ?array &$schemaWarnings = null,
    ): array;
    public function extractDirectiveArguments(
        DirectiveResolverInterface $directiveResolver,
        TypeResolverInterface $typeResolver,
        string $directive,
        ?array $variables = null,
        ?array &$schemaErrors = null,
        ?array &$schemaWarnings = null,
    ): array;
    public function extractFieldArgumentsForSchema(TypeResolverInterface $typeResolver, string $field, ?array $variables = null): array;
    public function extractDirectiveArgumentsForSchema(DirectiveResolverInterface $directiveResolver, TypeResolverInterface $typeResolver, string $directive, ?array $variables = null, bool $disableDynamicFields = false): array;
    public function extractFieldArgumentsForResultItem(
        TypeResolverInterface $typeResolver,
        object $resultItem,
        string $field,
        ?array $variables,
        ?array $expressions
    ): array;
    public function extractDirectiveArgumentsForResultItem(
        DirectiveResolverInterface $directiveResolver,
        TypeResolverInterface $typeResolver,
        object $resultItem,
        string $directive,
        array $variables,
        array $expressions
    ): array;
    public function maybeConvertFieldArgumentValue(mixed $fieldArgValue, ?array $variables = null): mixed;
    public function maybeConvertFieldArgumentArrayValue(mixed $fieldArgValue, ?array $variables = null): mixed;
}
