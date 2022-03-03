<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\ComponentModel\AttachableExtensions\AttachableExtensionInterface;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

interface DirectiveResolverInterface extends AttachableExtensionInterface, SchemaDirectiveResolverInterface
{
    /**
     * The classes of the ObjectTypeResolvers and/or InterfaceTypeResolvers
     * this DirectiveResolver adds directives to.
     * The list can contain both concrete and abstract classes (in which case all classes
     * extending from them will be selected)
     *
     * @return string[]
     */
    public function getRelationalTypeOrInterfaceTypeResolverClassesToAttachTo(): array;
    public function getDirectiveName(): string;
    /**
     * Invoked when creating the non-shared directive instance
     * to resolve a field in the pipeline
     */
    public function setDirective(string $directive): void;
    /**
     * Indicate to what fieldNames this directive can be applied.
     * Returning an empty array means all of them
     */
    public function getFieldNamesToApplyTo(): array;
    /**
     * Directives can be either of type "Schema" or "Query" and,
     * depending on one case or the other, might be exposed to the user.
     * By default, use the Query type
     */
    public function getDirectiveKind(): string;
    /**
     * Extract and validate the directive arguments
     */
    public function dissectAndValidateDirectiveForSchema(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array &$fieldDirectiveFields,
        array &$variables,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): array;

    /**
     * Enable the directiveResolver to validate the directive arguments in a custom way
     */
    public function validateDirectiveArgumentsForSchema(
        RelationalTypeResolverInterface $relationalTypeResolver,
        string $directiveName,
        array $directiveArgs,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): array;
    /**
     * Define where to place the directive in the directive execution pipeline
     * 2 directives are mandatory: Validate and ResolveAndMerge, which are executed in this order.
     * All other directives must indicate where to position themselves, using these 2 directives as anchors.
     * There are 3 positions:
     * 1. At the beginning, before the Validate pipeline
     * 2. In the middle, between the Validate and Resolve directives
     * 3. At the end, after the ResolveAndMerge directive
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
    public function resolveCanProcess(
        RelationalTypeResolverInterface $relationalTypeResolver,
        string $directiveName,
        array $directiveArgs,
        string $field,
        array &$variables
    ): bool;
    /**
     * Indicates if the directive can be added several times to the pipeline, or only once
     */
    public function isRepeatable(): bool;
    /**
     * Indicate if the directive needs to be passed $idsDataFields filled with data to be able to execute
     */
    public function needsIDsDataFieldsToExecute(): bool;
    /**
     * Execute the directive
     */
    public function resolveDirective(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $idsDataFields,
        array $succeedingPipelineDirectiveResolverInstances,
        array $objectIDItems,
        array $unionDBKeyIDs,
        array $previousDBItems,
        array &$succeedingPipelineIDsDataFields,
        array &$dbItems,
        array &$variables,
        array &$messages,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): void;
    /**
     * A directive can decide to not be added to the schema, eg: when it is repeated/implemented several times
     */
    public function skipExposingDirectiveInSchema(RelationalTypeResolverInterface $relationalTypeResolver): bool;
    public function skipExposingDirectiveArgInSchema(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): bool;
    public function getDirectiveSchemaDefinition(RelationalTypeResolverInterface $relationalTypeResolver): array;
    /**
     * Define if to use the version to decide if to process the directive or not
     */
    public function decideCanProcessBasedOnVersionConstraint(RelationalTypeResolverInterface $relationalTypeResolver): bool;
    /**
     * The version of the directive, using semantic versioning
     */
    public function getDirectiveVersion(RelationalTypeResolverInterface $relationalTypeResolver): ?string;
    public function hasDirectiveVersion(RelationalTypeResolverInterface $relationalTypeResolver): bool;
    public function getDirectiveVersionInputTypeResolver(RelationalTypeResolverInterface $relationalTypeResolver): ?InputTypeResolverInterface;

    /**
     * @return FeedbackItemResolution[] Errors
     */
    public function resolveDirectiveValidationErrors(
        RelationalTypeResolverInterface $relationalTypeResolver,
        string $directiveName,
        array $directiveArgs
    ): array;
    public function resolveDirectiveWarningDescription(RelationalTypeResolverInterface $relationalTypeResolver): ?FeedbackItemResolution;
    public function getDirectiveDeprecationMessage(RelationalTypeResolverInterface $relationalTypeResolver): ?string;
}
