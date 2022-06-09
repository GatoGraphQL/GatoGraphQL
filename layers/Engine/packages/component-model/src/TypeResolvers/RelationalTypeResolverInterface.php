<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers;

use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use SplObjectStorage;

interface RelationalTypeResolverInterface extends ConcreteTypeResolverInterface
{
    /**
     * All objects MUST have an ID. `null` is supported for the UnionTypeResolver,
     * when it cannot find a resolver to handle the object.
     *
     * @return string|int|null the ID of the passed object, or `null` if there is no resolver to handle it (for the UnionTypeResolver)
     */
    public function getID(object $object): string | int | null;
    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface;
    /**
     * @return InterfaceTypeResolverInterface[]
     */
    public function getImplementedInterfaceTypeResolvers(): array;
    /**
     * @param string|int|array<string|int> $dbObjectIDOrIDs
     * @return string|int|array<string|int>
     */
    public function getQualifiedDBObjectIDOrIDs(string | int | array $dbObjectIDOrIDs): string | int | array;
    /**
     * @param array<string|int,EngineIterationFieldSet> $idsDataFields
     */
    public function enqueueFillingObjectsFromIDs(array $idsDataFields): void;
    /**
     * @param array<string|int,EngineIterationFieldSet> $ids_data_fields
     */
    public function fillObjects(
        array $ids_data_fields,
        array $unionDBKeyIDs,
        array $previousDBItems,
        array &$dbItems,
        array &$variables,
        array &$messages,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): array;
    /**
     * @param array<string, mixed> $variables
     * @param array<string, mixed> $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        object $object,
        string $field,
        array $variables,
        array $expressions,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
        array $options = []
    ): mixed;
    /**
     * Validate and resolve the fieldDirectives into an array, each item containing:
     * 1. the directiveResolverInstance
     * 2. its fieldDirective
     * 3. the fields it affects
     *
     * @param array<string,FieldInterface[]> $fieldDirectiveFields
     */
    public function resolveDirectivesIntoPipelineData(
        array $fieldDirectives,
        array &$fieldDirectiveFields,
        array &$variables,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): array;
    /**
     * @param FieldInterface[] $fieldDirectiveFields
     * @return SplObjectStorage<FieldInterface,DirectiveResolverInterface>|null
     */
    public function getDirectiveResolverInstancesForDirective(string $fieldDirective, array $fieldDirectiveFields, array &$variables): ?array;
    /**
     * Array of directive name => resolver
     *
     * @return array<string, DirectiveResolverInterface>
     */
    public function getSchemaDirectiveResolvers(bool $global): array;
}
