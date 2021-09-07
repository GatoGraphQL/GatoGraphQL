<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\ComponentModel\AttachableExtensions\AttachableExtensionInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

interface DirectiveResolverInterface extends AttachableExtensionInterface
{
    public function getDirectiveName(): string;
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
    public function getDirectiveType(): string;
    /**
     * Extract and validate the directive arguments
     */
    public function dissectAndValidateDirectiveForSchema(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array &$fieldDirectiveFields,
        array &$variables,
        array &$schemaErrors,
        array &$schemaWarnings,
        array &$schemaDeprecations,
        array &$schemaNotices,
        array &$schemaTraces
    ): array;

    /**
     * Enable the directiveResolver to validate the directive arguments in a custom way
     */
    public function validateDirectiveArgumentsForSchema(
        RelationalTypeResolverInterface $relationalTypeResolver,
        string $directiveName,
        array $directiveArgs,
        array &$schemaErrors,
        array &$schemaWarnings,
        array &$schemaDeprecations
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
        array &$idsDataFields,
        array &$succeedingPipelineIDsDataFields,
        array &$succeedingPipelineDirectiveResolverInstances,
        array &$resultIDItems,
        array &$unionDBKeyIDs,
        array &$dbItems,
        array &$previousDBItems,
        array &$variables,
        array &$messages,
        array &$dbErrors,
        array &$dbWarnings,
        array &$dbDeprecations,
        array &$dbNotices,
        array &$dbTraces,
        array &$schemaErrors,
        array &$schemaWarnings,
        array &$schemaDeprecations,
        array &$schemaNotices,
        array &$schemaTraces
    ): void;
    /**
     * A directive can decide to not be added to the schema, eg: when it is repeated/implemented several times
     */
    public function skipAddingToSchemaDefinition(): bool;
    public function getSchemaDefinitionForDirective(RelationalTypeResolverInterface $relationalTypeResolver): array;
    /**
     * Define if to use the version to decide if to process the directive or not
     */
    public function decideCanProcessBasedOnVersionConstraint(RelationalTypeResolverInterface $relationalTypeResolver): bool;
    /**
     * The version of the directive, using semantic versioning
     */
    public function getSchemaDirectiveVersion(RelationalTypeResolverInterface $relationalTypeResolver): ?string;
    public function enableOrderedSchemaDirectiveArgs(RelationalTypeResolverInterface $relationalTypeResolver): bool;
    /**
     * Indicate if the directive is global (i.e. it can be applied to all fields, for all typeResolvers)
     */
    public function isGlobal(RelationalTypeResolverInterface $relationalTypeResolver): bool;

    public function resolveSchemaValidationErrorDescriptions(
        RelationalTypeResolverInterface $relationalTypeResolver,
        string $directiveName,
        array $directiveArgs = []
    ): ?array;
    public function resolveSchemaDirectiveWarningDescription(RelationalTypeResolverInterface $relationalTypeResolver): ?string;
    public function getSchemaDirectiveDeprecationDescription(RelationalTypeResolverInterface $relationalTypeResolver): ?string;
}
