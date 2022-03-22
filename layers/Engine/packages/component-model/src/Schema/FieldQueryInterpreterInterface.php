<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Schema;

use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\FieldQuery\FieldQueryInterpreterInterface as UpstreamFieldQueryInterpreterInterface;

interface FieldQueryInterpreterInterface extends UpstreamFieldQueryInterpreterInterface
{
    /**
     * If two different fields for the same type have the same fieldOutputKey, then
     * add a counter to the second one, so each of them is unique.
     * That is to avoid overriding the previous value, as when doing:
     *
     *   ?query=posts.title|self.excerpt@title
     *
     * In this case, the value of the excerpt would override the value of the title,
     * since they both have fieldOutputKey "title".
     *
     * If the TypeResolver is of Union type, because the data for the object
     * is stored under the target ObjectTypeResolver, then the unique field name
     * must be retrieved against the target ObjectTypeResolver
     */
    public function getUniqueFieldOutputKey(
        RelationalTypeResolverInterface $relationalTypeResolver,
        string $field,
        object $object,
    ): string;
    /**
     * If the TypeResolver is of Union type, and we don't have the object
     * (eg: when printing the configuration), then generate a list of the
     * unique field outputs for all the target ObjectTypeResolvers.
     *
     * If the TypeResolver is an Object type, to respect the same response,
     * return an array of a single element, with its own unique field output.
     *
     * @return array<string,string>
     */
    public function getTargetObjectTypeUniqueFieldOutputKeys(
        RelationalTypeResolverInterface $relationalTypeResolver,
        string $field,
    ): array;
    public function getUniqueFieldOutputKeyByObjectTypeResolver(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $field,
    ): string;
    public function getUniqueFieldOutputKeyByTypeOutputDBKey(string $dbKey, string $field): string;
    /**
     * Extract field args without using the schema. It is needed to find out which fieldResolver will process a field, where we can't depend on the schema since this one needs to know who the fieldResolver is, creating an infitine loop
     */
    public function extractStaticFieldArguments(string $field, ?array $variables = null): array;
    public function extractStaticDirectiveArguments(string $directive, ?array $variables = null): array;
    /**
     * Return `null` if there is no resolver for the field
     */
    public function extractFieldArguments(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $field,
        array $variables,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): ?array;
    public function extractDirectiveArguments(
        DirectiveResolverInterface $directiveResolver,
        RelationalTypeResolverInterface $relationalTypeResolver,
        string $directive,
        array $variables,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): array;
    public function extractFieldArgumentsForSchema(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $field,
        array $variables,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): array;
    public function extractDirectiveArgumentsForSchema(
        DirectiveResolverInterface $directiveResolver,
        RelationalTypeResolverInterface $relationalTypeResolver,
        string $directive,
        array $fieldDirectiveFields,
        array $variables,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
        bool $disableDynamicFields = false
    ): array;
    public function extractFieldArgumentsForObject(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $field,
        array $variables,
        array $expressions,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): array;
    public function extractDirectiveArgumentsForObject(
        DirectiveResolverInterface $directiveResolver,
        RelationalTypeResolverInterface $relationalTypeResolver,
        object $object,
        array $fields,
        string $directive,
        array $variables,
        array $expressions,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): array;
    public function maybeConvertFieldArgumentValue(mixed $fieldArgValue, ?array $variables = null): mixed;
    public function maybeConvertFieldArgumentArrayOrObjectValue(mixed $fieldArgValue, ?array $variables = null): mixed;
    public function resolveExpression(
        RelationalTypeResolverInterface $relationalTypeResolver,
        mixed $fieldArgValue,
        ?array $expressions,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed;
}
