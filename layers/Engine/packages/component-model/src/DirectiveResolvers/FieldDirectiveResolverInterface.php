<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\ComponentModel\AttachableExtensions\AttachableExtensionInterface;
use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessProviderInterface;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use SplObjectStorage;

interface FieldDirectiveResolverInterface extends DirectiveResolverInterface, AttachableExtensionInterface, SchemaFieldDirectiveResolverInterface
{
    /**
     * The classes of the ObjectTypeResolvers and/or InterfaceTypeResolvers
     * this DirectiveResolver adds directives to.
     * The list can contain both concrete and abstract classes (in which case all classes
     * extending from them will be selected)
     *
     * @return array<class-string<InterfaceTypeResolverInterface|RelationalTypeResolverInterface>>
     */
    public function getRelationalTypeOrInterfaceTypeResolverClassesToAttachTo(): array;

    /**
     * Validate and initialize the Directive, such as adding
     * the default values for Arguments which were not provided
     * in the query.
     *
     * @param FieldInterface[] $fields
     */
    public function prepareDirective(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $fields,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): void;

    /**
     * After calling `prepareDirective`, indicate if casting
     * the Directive Arguments produced any error.
     */
    public function hasValidationErrors(): bool;

    /**
     * Indicate to what fieldNames this directive can be applied.
     * Returning an empty array means all of them
     *
     * @return string[]
     */
    public function getFieldNamesToApplyTo(): array;

    /**
     * Directives can be either of type "Schema" or "Query" and,
     * depending on one case or the other, might be exposed to the user.
     * By default, use the Query type
     */
    public function getDirectiveKind(): string;

    /**
     * The FieldDirectiveResolver can handle Field Directives and,
     * in addition, Operation Directives.
     *
     * This method indicates the behavior of the FieldDirectiveResolver,
     * indicating one of the following:
     *
     * - Behave as Field (default)
     * - Behave as Field and Operation
     * - Behave as Operation
     *
     * Based on this value, the Directive Locations will be reflected
     * as defined by the GraphQL spec.
     */
    public function getFieldDirectiveBehavior(): string;

    /**
     * Define where to place the directive in the directive execution pipeline
     *
     * 2 directives are mandatory, and executed in this order:
     *
     *   1. ResolveAndMerge: to resolve the field and place the data into the DB object
     *   2. SerializeLeafOutputTypeValues: to serialize Scalar and Enum Type values
     *
     * All other directives must indicate where to position themselves,
     * using these 2 directives as anchors.
     *
     * There are 6 positions:
     *
     *   1. At the very beginning
     *   2. Before the Validate directive
     *   3. Between the Validate and Resolve directives
     *   4. Between the Resolve and Serialize directives
     *   5. After the Serialize directive
     *   6. At the very end
     *
     * In the "serialize" step, the directive takes the objects
     * stored in $resolvedIDFieldValues, such as a DateTime object,
     * and converts them to string for printing in the response.
     */
    public function getPipelinePosition(): string;

    /**
     * This is the equivalent to `__invoke` in League\Pipeline\StageInterface
     *
     * @param mixed[] $payload
     * @return mixed[]
     */
    public function resolveDirectivePipelinePayload(array $payload): array;

    /**
     * Indicate if the directiveResolver can process the directive with the given name and args
     */
    public function resolveCanProcessDirective(
        RelationalTypeResolverInterface $relationalTypeResolver,
        Directive $directive,
    ): bool;

    /**
     * Indicate if the directiveResolver can process the directive with the given name and args
     */
    public function resolveCanProcessField(
        RelationalTypeResolverInterface $relationalTypeResolver,
        FieldInterface $field,
    ): bool;

    /**
     * Indicate if the directive needs to be passed $idFieldSet filled with data to be able to execute
     */
    public function needsSomeIDFieldToExecute(): bool;

    /**
     * Execute the directive
     *
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     * @param array<array<string|int,EngineIterationFieldSet>> $succeedingPipelineIDFieldSet
     * @param array<string|int,object> $idObjects
     * @param array<FieldDataAccessProviderInterface> $succeedingPipelineFieldDataAccessProviders
     * @param array<string,array<string|int,SplObjectStorage<FieldInterface,mixed>>> $previouslyResolvedIDFieldValues
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $resolvedIDFieldValues
     * @param array<\PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface> $succeedingPipelineFieldDirectiveResolvers
     * @param array<string,array<string|int,SplObjectStorage<FieldInterface,array<string|int>>>> $unionTypeOutputKeyIDs
     * @param array<string,mixed> $messages
     */
    public function resolveDirective(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $idFieldSet,
        FieldDataAccessProviderInterface $fieldDataAccessProvider,
        array $succeedingPipelineFieldDirectiveResolvers,
        array $idObjects,
        array $unionTypeOutputKeyIDs,
        array $previouslyResolvedIDFieldValues,
        array &$succeedingPipelineIDFieldSet,
        array &$succeedingPipelineFieldDataAccessProviders,
        array &$resolvedIDFieldValues,
        array &$messages,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): void;

    /**
     * A directive can decide to not be added to the schema, eg: when it is repeated/implemented several times
     */
    public function skipExposingDirectiveInSchema(RelationalTypeResolverInterface $relationalTypeResolver): bool;
    public function skipExposingDirectiveArgInSchema(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): bool;
    /**
     * @return array<string,mixed>
     */
    public function getDirectiveSchemaDefinition(RelationalTypeResolverInterface $relationalTypeResolver): array;
    /**
     * The version of the directive, using semantic versioning
     */
    public function getDirectiveVersion(RelationalTypeResolverInterface $relationalTypeResolver): ?string;
    public function hasDirectiveVersion(RelationalTypeResolverInterface $relationalTypeResolver): bool;

    public function getDirectiveDeprecationMessage(RelationalTypeResolverInterface $relationalTypeResolver): ?string;

    /**
     * Name for the directive arg to indicate which additional fields
     * must be affected by the directive, by indicating their relative position.
     *
     * Eg: { posts { excerpt content @strTranslate(affectAdditionalFieldsUnderPos: [1]) } }
     *
     * @return string Name of the directiveArg, or `null` to disable this feature for the directive
     */
    public function getAffectAdditionalFieldsUnderPosArgumentName(): ?string;
}
