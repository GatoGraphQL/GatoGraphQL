<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers;

use PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface;
use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
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
    public function getID(object $object): string|int|null;
    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface;
    /**
     * @return InterfaceTypeResolverInterface[]
     */
    public function getImplementedInterfaceTypeResolvers(): array;
    /**
     * @param string|int|array<string|int> $objectIDOrIDs
     * @return string|int|array<string|int>
     */
    public function getQualifiedDBObjectIDOrIDs(string|int|array $objectIDOrIDs): string|int|array;
    /**
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     */
    public function enqueueFillingObjectsFromIDs(array $idFieldSet): void;
    /**
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     * @param array<string,array<string|int,SplObjectStorage<FieldInterface,mixed>>> $previouslyResolvedIDFieldValues
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $resolvedIDFieldValues
     * @return array<string|int,object>
     * @param array<string,array<string|int,SplObjectStorage<FieldInterface,array<string|int>>>> $unionTypeOutputKeyIDs
     * @param array<string,mixed> $messages
     */
    public function fillObjects(
        array $idFieldSet,
        array $unionTypeOutputKeyIDs,
        array $previouslyResolvedIDFieldValues,
        array &$resolvedIDFieldValues,
        array &$messages,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): array;

    public function resolveValue(
        object $object,
        FieldInterface|FieldDataAccessorInterface $fieldOrFieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed;

    /**
     * Validate and resolve the directives into an array, each item containing:
     *
     *   1. the directiveResolverInstance
     *   2. its directive
     *   3. the fields it affects
     *
     * @param Directive[] $directives
     * @param SplObjectStorage<Directive,FieldInterface[]> $directiveFields
     * @return SplObjectStorage<FieldDirectiveResolverInterface,FieldInterface[]>
     */
    public function resolveDirectivesIntoPipelineData(
        array $directives,
        SplObjectStorage $directiveFields,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): SplObjectStorage;

    /**
     * Array of directive name => resolver
     *
     * @return array<string,FieldDirectiveResolverInterface>
     */
    public function getSchemaFieldDirectiveResolvers(bool $global): array;
}
