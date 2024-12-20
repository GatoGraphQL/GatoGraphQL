<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

use Exception;
use PoP\ComponentModel\App;
use PoP\ComponentModel\AttachableExtensions\AttachableExtensionManagerInterface;
use PoP\ComponentModel\AttachableExtensions\AttachableExtensionTrait;
use PoP\ComponentModel\DirectivePipeline\DirectivePipelineUtils;
use PoP\ComponentModel\Directives\DirectiveKinds;
use PoP\ComponentModel\Directives\DirectiveLocations;
use PoP\ComponentModel\Directives\FieldDirectiveBehaviors;
use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\FeedbackItemProviders\ErrorFeedbackItemProvider;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\Feedback\ObjectResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectResolutionFeedbackStore;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\Feedback\SchemaFeedback;
use PoP\ComponentModel\Feedback\SchemaFeedbackStore;
use PoP\ComponentModel\HelperServices\SemverHelperServiceInterface;
use PoP\ComponentModel\Module;
use PoP\ComponentModel\ModuleConfiguration;
use PoP\ComponentModel\QueryResolution\DirectiveDataAccessor;
use PoP\ComponentModel\QueryResolution\DirectiveDataAccessorInterface;
use PoP\ComponentModel\QueryResolution\FieldDataAccessProviderInterface;
use PoP\ComponentModel\Resolvers\CheckDangerouslyNonSpecificScalarTypeFieldOrFieldDirectiveResolverTrait;
use PoP\ComponentModel\Resolvers\FieldOrDirectiveSchemaDefinitionResolverTrait;
use PoP\ComponentModel\Resolvers\ObjectTypeOrFieldDirectiveResolverTrait;
use PoP\ComponentModel\Resolvers\WithVersionConstraintFieldOrFieldDirectiveResolverTrait;
use PoP\ComponentModel\Schema\SchemaCastingServiceInterface;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\StaticHelpers\MethodHelpers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\PipelinePositions;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\DangerouslyNonSpecificScalarTypeScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
use PoP\ComponentModel\Versioning\VersioningServiceInterface;
use PoP\GraphQLParser\Exception\AbstractQueryException;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLSpecErrorFeedbackItemProvider;
use PoP\GraphQLParser\Module as GraphQLParserModule;
use PoP\GraphQLParser\ModuleConfiguration as GraphQLParserModuleConfiguration;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\Exception\AbstractClientException;
use PoP\Root\FeedbackItemProviders\GenericFeedbackItemProvider;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use SplObjectStorage;

/**
 * The GraphQL server resolves only FieldDirectiveResolvers
 * via the directive pipeline.
 *
 * FieldDirectiveResolvers can also handle Operation Directives,
 * by having these be duplicated into the SuperRoot type fields.
 */
abstract class AbstractFieldDirectiveResolver extends AbstractDirectiveResolver implements FieldDirectiveResolverInterface
{
    use AttachableExtensionTrait;
    use RemoveIDFieldSetFieldDirectiveResolverTrait;
    use FieldOrDirectiveSchemaDefinitionResolverTrait;
    use WithVersionConstraintFieldOrFieldDirectiveResolverTrait;
    use CheckDangerouslyNonSpecificScalarTypeFieldOrFieldDirectiveResolverTrait;
    use ObjectTypeOrFieldDirectiveResolverTrait;

    /** @var array<string,array<string,InputTypeResolverInterface>> */
    protected array $consolidatedDirectiveArgNameTypeResolversCache = [];
    /** @var array<string,string|null> */
    protected array $consolidatedDirectiveArgDescriptionCache = [];
    /** @var array<string,mixed> */
    protected array $consolidatedDirectiveArgDefaultValueCache = [];
    /** @var array<string,int> */
    protected array $consolidatedDirectiveArgTypeModifiersCache = [];
    /** @var array<string,array<string,mixed>> */
    protected array $consolidatedDirectiveArgExtensionsCache = [];
    /** @var array<string,array<string,mixed>> */
    protected array $schemaDirectiveArgsCache = [];

    /**
     * To be set only if there were no validation errors.
     */
    protected DirectiveDataAccessorInterface $directiveDataAccessor;
    protected bool $hasValidationErrors;
    /**
     * When the directive args have promises, they must be
     * validated. Cache the validation result.
     */
    protected ?bool $validatedDirectiveArgsHaveErrors = null;

    /**
     * @var array<string,array<string,mixed>>
     */
    protected array $schemaDefinitionForDirectiveCache = [];

    private ?SemverHelperServiceInterface $semverHelperService = null;
    private ?AttachableExtensionManagerInterface $attachableExtensionManager = null;
    private ?DangerouslyNonSpecificScalarTypeScalarTypeResolver $dangerouslyNonSpecificScalarTypeScalarTypeResolver = null;
    private ?VersioningServiceInterface $versioningService = null;
    private ?IntScalarTypeResolver $intScalarTypeResolver = null;
    private ?SchemaCastingServiceInterface $schemaCastingService = null;

    final protected function getSemverHelperService(): SemverHelperServiceInterface
    {
        if ($this->semverHelperService === null) {
            /** @var SemverHelperServiceInterface */
            $semverHelperService = $this->instanceManager->getInstance(SemverHelperServiceInterface::class);
            $this->semverHelperService = $semverHelperService;
        }
        return $this->semverHelperService;
    }
    final protected function getAttachableExtensionManager(): AttachableExtensionManagerInterface
    {
        if ($this->attachableExtensionManager === null) {
            /** @var AttachableExtensionManagerInterface */
            $attachableExtensionManager = $this->instanceManager->getInstance(AttachableExtensionManagerInterface::class);
            $this->attachableExtensionManager = $attachableExtensionManager;
        }
        return $this->attachableExtensionManager;
    }
    final protected function getDangerouslyNonSpecificScalarTypeScalarTypeResolver(): DangerouslyNonSpecificScalarTypeScalarTypeResolver
    {
        if ($this->dangerouslyNonSpecificScalarTypeScalarTypeResolver === null) {
            /** @var DangerouslyNonSpecificScalarTypeScalarTypeResolver */
            $dangerouslyNonSpecificScalarTypeScalarTypeResolver = $this->instanceManager->getInstance(DangerouslyNonSpecificScalarTypeScalarTypeResolver::class);
            $this->dangerouslyNonSpecificScalarTypeScalarTypeResolver = $dangerouslyNonSpecificScalarTypeScalarTypeResolver;
        }
        return $this->dangerouslyNonSpecificScalarTypeScalarTypeResolver;
    }
    final protected function getVersioningService(): VersioningServiceInterface
    {
        if ($this->versioningService === null) {
            /** @var VersioningServiceInterface */
            $versioningService = $this->instanceManager->getInstance(VersioningServiceInterface::class);
            $this->versioningService = $versioningService;
        }
        return $this->versioningService;
    }
    final protected function getIntScalarTypeResolver(): IntScalarTypeResolver
    {
        if ($this->intScalarTypeResolver === null) {
            /** @var IntScalarTypeResolver */
            $intScalarTypeResolver = $this->instanceManager->getInstance(IntScalarTypeResolver::class);
            $this->intScalarTypeResolver = $intScalarTypeResolver;
        }
        return $this->intScalarTypeResolver;
    }
    final protected function getSchemaCastingService(): SchemaCastingServiceInterface
    {
        if ($this->schemaCastingService === null) {
            /** @var SchemaCastingServiceInterface */
            $schemaCastingService = $this->instanceManager->getInstance(SchemaCastingServiceInterface::class);
            $this->schemaCastingService = $schemaCastingService;
        }
        return $this->schemaCastingService;
    }

    /**
     * @return string[]
     */
    final public function getClassesToAttachTo(): array
    {
        return $this->getRelationalTypeOrInterfaceTypeResolverClassesToAttachTo();
    }

    /**
     * Initialize the Directive with additional information,
     * such as adding the default Argument AST objects which
     * were not provided in the query.
     *
     * @param FieldInterface[] $fields
     */
    public function prepareDirective(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $fields,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): void {
        $directiveArgs = $this->getDirectiveArgs(
            $relationalTypeResolver,
            $fields,
            $engineIterationFeedbackStore,
        );
        $this->setHasValidationErrors($directiveArgs === null);
        if ($directiveArgs === null) {
            return;
        }
        $this->directiveDataAccessor = $this->createDirectiveDataAccessor($directiveArgs);
    }

    /**
     * @param array<string,mixed> $directiveArgs
     */
    public function createDirectiveDataAccessor(
        array $directiveArgs,
    ): DirectiveDataAccessorInterface {
        return new DirectiveDataAccessor(
            $directiveArgs,
        );
    }

    /**
     * After calling `prepareDirective`, indicate if casting
     * the Directive Arguments produced any error.
     */
    public function hasValidationErrors(): bool
    {
        return $this->hasValidationErrors;
    }

    protected function setHasValidationErrors(bool $hasValidationErrors): void
    {
        $this->hasValidationErrors = $hasValidationErrors;
    }

    /**
     * Extract the FieldArgs into its corresponding DirectiveDataAccessor, which integrates
     * within the default values and coerces them according to the schema.
     *
     * @param FieldInterface[] $fields
     * @return array<string,mixed>|null Null if there was an error validating the directive
     */
    protected function getDirectiveArgs(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $fields,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): ?array {
        $directiveArgs = $this->directive->getArgumentKeyValues();
        $directiveArgsSchemaDefinition = $this->getDirectiveArgumentsSchemaDefinition($relationalTypeResolver);

        /**
         * Add the default Argument values
         */
        $directiveArgumentNameDefaultValues = $this->getFieldOrDirectiveArgumentNameDefaultValues($directiveArgsSchemaDefinition);
        $directiveArgs = $this->addDefaultFieldOrDirectiveArguments(
            $directiveArgs,
            $directiveArgumentNameDefaultValues,
        );

        /**
         * Cast the Arguments, return if any of them produced an error
         */
        $objectTypeFieldResolutionFeedbackStore = new ObjectTypeFieldResolutionFeedbackStore();
        $directiveArgs = $this->getSchemaCastingService()->castArguments(
            $directiveArgs,
            $directiveArgsSchemaDefinition,
            $this->directive,
            $objectTypeFieldResolutionFeedbackStore,
        );
        $engineIterationFeedbackStore->schemaFeedbackStore->incorporateFromObjectTypeFieldResolutionFeedbackStore(
            $objectTypeFieldResolutionFeedbackStore,
            $relationalTypeResolver,
            $fields,
        );
        if ($objectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
            return null;
        }

        /**
         * Validations can only be performed if there are no promises.
         * If there are, the validation will be performed later on.
         */
        if ($this->directive->hasArgumentReferencingPromise()) {
            return $directiveArgs;
        }

        /**
         * Perform validations
         */
        $errorCount = $engineIterationFeedbackStore->getErrorCount();
        $this->validateDirectiveArgs(
            $directiveArgs,
            $relationalTypeResolver,
            $fields,
            $engineIterationFeedbackStore,
        );
        if ($engineIterationFeedbackStore->getErrorCount() > $errorCount) {
            return null;
        }

        return $directiveArgs;
    }

    /**
     * Whenever a directive arg contains a promise to be resolved on
     * the document, the directiveArgs and its validation must be
     * performed only once the promise has been resolved.
     *
     * Otherwise, if there are no promises, all the validations have
     * already been performed.
     *
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     * @return array<string,mixed>|null Null if there was an error validating the directive
     */
    protected function getResolvedDirectiveArgs(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $idFieldSet,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): ?array {
        $hasArgumentReferencingPromise = $this->directive->hasArgumentReferencingPromise();
        if ($hasArgumentReferencingPromise && $this->validatedDirectiveArgsHaveErrors) {
            return null;
        }
        $directiveArgs = $this->directiveDataAccessor->getDirectiveArgs();
        if ($hasArgumentReferencingPromise && $this->validatedDirectiveArgsHaveErrors === null) {
            /**
             * Perform validations
             */
            $errorCount = $engineIterationFeedbackStore->getErrorCount();
            $this->validateDirectiveArgs(
                $directiveArgs,
                $relationalTypeResolver,
                MethodHelpers::getFieldsFromIDFieldSet($idFieldSet),
                $engineIterationFeedbackStore,
            );
            if ($engineIterationFeedbackStore->getErrorCount() > $errorCount) {
                $this->validatedDirectiveArgsHaveErrors = true;
                return null;
            }
            $this->validatedDirectiveArgsHaveErrors = false;
        }
        return $directiveArgs;
    }

    /**
     * Whenever a directive arg receives a promise to be resolved on
     * an object, the directiveArgs and its validation must be
     * performed for that object.
     *
     * @return array<string,mixed>|null Null if there was an error validating the directive
     */
    protected function getResolvedDirectiveArgsForObjectAndField(
        RelationalTypeResolverInterface $relationalTypeResolver,
        FieldInterface $field,
        string|int $id,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): ?array {
        $this->loadObjectResolvedDynamicVariablesInAppState($field, $id);
        $this->directiveDataAccessor->resetDirectiveArgs();
        $directiveArgs = $this->directiveDataAccessor->getDirectiveArgs();

        /**
         * Perform validations
         */
        $errorCount = $engineIterationFeedbackStore->getErrorCount();
        $this->validateDirectiveArgs(
            $directiveArgs,
            $relationalTypeResolver,
            [$field],
            $engineIterationFeedbackStore,
        );
        if ($engineIterationFeedbackStore->getErrorCount() > $errorCount) {
            return null;
        }

        return $directiveArgs;
    }

    /**
     * Validate the directive data
     *
     * @param array<string,mixed> $directiveArgs
     * @param FieldInterface[] $fields
     */
    protected function validateDirectiveArgs(
        array $directiveArgs,
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $fields,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): void {
        /** @var array<string,mixed> */
        $directiveArgsSchemaDefinition = $this->getDirectiveArgumentsSchemaDefinition($relationalTypeResolver);

        /**
         * Validations:
         *
         * - no mandatory arg is missing
         * - no non-existing arg has been provided
         */
        $errorCount = $engineIterationFeedbackStore->getErrorCount();
        $this->validateNonMissingMandatoryDirectiveArguments(
            $directiveArgs,
            $directiveArgsSchemaDefinition,
            $relationalTypeResolver,
            $fields,
            $engineIterationFeedbackStore,
        );
        $this->validateOnlyExistingDirectiveArguments(
            $directiveArgs,
            $directiveArgsSchemaDefinition,
            $relationalTypeResolver,
            $fields,
            $engineIterationFeedbackStore,
        );
        if ($engineIterationFeedbackStore->getErrorCount() > $errorCount) {
            return;
        }

        /**
         * Validations:
         *
         * - constraints of the arguments
         */
        $this->validateDirectiveArgumentConstraints(
            $directiveArgs,
            $relationalTypeResolver,
            $fields,
            $engineIterationFeedbackStore,
        );
    }

    /**
     * If the key is missing or is `null` then it's an error.
     *
     *   Eg (arg `tags` is mandatory):
     *   `{ id @skip(if: null) }` or `{ id @skip }`
     *
     * If the value is empty, such as '""' or [], then it's OK.
     *
     *   Eg: `{ id @skip(if: "") }` <= will be coerced to `false`
     *
     * @param array<string,mixed> $directiveArgsSchemaDefinition
     * @param FieldInterface[] $fields
     * @param array<string,mixed> $directiveArgs
     */
    private function validateNonMissingMandatoryDirectiveArguments(
        array $directiveArgs,
        array $directiveArgsSchemaDefinition,
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $fields,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): void {
        $mandatoryDirectiveArgNames = $this->getFieldOrDirectiveMandatoryArgumentNames($directiveArgsSchemaDefinition);
        $missingMandatoryDirectiveArgNames = array_values(array_filter(
            $mandatoryDirectiveArgNames,
            fn (string $directiveArgName) => !array_key_exists($directiveArgName, $directiveArgs)
        ));
        foreach ($missingMandatoryDirectiveArgNames as $missingMandatoryDirectiveArgName) {
            $engineIterationFeedbackStore->schemaFeedbackStore->addError(
                new SchemaFeedback(
                    new FeedbackItemResolution(
                        GraphQLSpecErrorFeedbackItemProvider::class,
                        GraphQLSpecErrorFeedbackItemProvider::E_5_4_2_1_C,
                        [
                            $missingMandatoryDirectiveArgName,
                            $this->directive->getName(),
                        ]
                    ),
                    $this->directive->getArgument($missingMandatoryDirectiveArgName)?->getValueAST()
                        ?? $this->directive->getArgument($missingMandatoryDirectiveArgName)
                        ?? $this->directive,
                    $relationalTypeResolver,
                    $fields,
                )
            );
        }

        $mandatoryButNullableDirectiveArgNames = $this->getFieldOrDirectiveMandatoryButNullableArgumentNames($directiveArgsSchemaDefinition);
        $nullNonNullableDirectiveArgNames = array_values(array_filter(
            $mandatoryDirectiveArgNames,
            fn (string $directiveArgName) => array_key_exists($directiveArgName, $directiveArgs) && $directiveArgs[$directiveArgName] === null && !in_array($directiveArgName, $mandatoryButNullableDirectiveArgNames)
        ));
        foreach ($nullNonNullableDirectiveArgNames as $nullNonNullableDirectiveArgName) {
            $engineIterationFeedbackStore->schemaFeedbackStore->addError(
                new SchemaFeedback(
                    new FeedbackItemResolution(
                        GraphQLSpecErrorFeedbackItemProvider::class,
                        GraphQLSpecErrorFeedbackItemProvider::E_5_4_2_1_D,
                        [
                            $nullNonNullableDirectiveArgName,
                            $this->directive->getName(),
                        ]
                    ),
                    $this->directive->getArgument($nullNonNullableDirectiveArgName)?->getValueAST()
                        ?? $this->directive->getArgument($nullNonNullableDirectiveArgName)
                        ?? $this->directive,
                    $relationalTypeResolver,
                    $fields,
                )
            );
        }
    }

    /**
     * Return an error if the query contains argument(s) that
     * does not exist in the directive.
     *
     * @param array<string,mixed> $directiveArgsSchemaDefinition
     * @param FieldInterface[] $fields
     * @param array<string,mixed> $directiveArgs
     */
    private function validateOnlyExistingDirectiveArguments(
        array $directiveArgs,
        array $directiveArgsSchemaDefinition,
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $fields,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): void {
        $nonExistingArgNames = array_values(array_diff(
            array_keys($directiveArgs),
            array_keys($directiveArgsSchemaDefinition)
        ));
        foreach ($nonExistingArgNames as $nonExistingArgName) {
            $engineIterationFeedbackStore->schemaFeedbackStore->addError(
                new SchemaFeedback(
                    new FeedbackItemResolution(
                        GraphQLSpecErrorFeedbackItemProvider::class,
                        GraphQLSpecErrorFeedbackItemProvider::E_5_4_1_B,
                        [
                            $this->directive->getName(),
                            $nonExistingArgName,
                        ]
                    ),
                    $this->directive->getArgument($nonExistingArgName) ?? $this->directive,
                    $relationalTypeResolver,
                    $fields,
                )
            );
        }
    }

    /**
     * Validate the constraints for the directive arguments
     *
     * @param FieldInterface[] $fields
     * @param array<string,mixed> $directiveArgs
     */
    private function validateDirectiveArgumentConstraints(
        array $directiveArgs,
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $fields,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): void {
        $objectTypeFieldResolutionFeedbackStore = new ObjectTypeFieldResolutionFeedbackStore();
        $directiveArgNameTypeResolvers = $this->getConsolidatedDirectiveArgNameTypeResolvers($relationalTypeResolver);
        foreach ($directiveArgs as $argName => $argValue) {
            $directiveArgTypeResolver = $directiveArgNameTypeResolvers[$argName];
            $astNode = $this->directive->getArgument($argName) ?? $this->directive;
            /**
             * If the arg is an InputObject, let it perform validations on its input fields.
             */
            if ($directiveArgTypeResolver instanceof InputObjectTypeResolverInterface) {
                $directiveArgTypeResolver->validateInputValue(
                    $argValue,
                    $astNode,
                    $objectTypeFieldResolutionFeedbackStore,
                );
            }
            $this->validateDirectiveArgValue(
                $argName,
                $argValue,
                $astNode,
                $directiveArgs,
                $relationalTypeResolver,
                $fields,
                $objectTypeFieldResolutionFeedbackStore,
            );
        }
        $engineIterationFeedbackStore->schemaFeedbackStore->incorporateFromObjectTypeFieldResolutionFeedbackStore(
            $objectTypeFieldResolutionFeedbackStore,
            $relationalTypeResolver,
            $fields,
        );
    }

    /**
     * Get the directive arguments which have a default value.
     *
     * @return array<string,mixed>|null
     */
    final protected function getDirectiveArgumentNameDefaultValues(RelationalTypeResolverInterface $relationalTypeResolver): ?array
    {
        $directiveArgsSchemaDefinition = $this->getDirectiveArgumentsSchemaDefinition($relationalTypeResolver);
        return $this->getFieldOrDirectiveArgumentNameDefaultValues($directiveArgsSchemaDefinition);
    }

    /**
     * @return array<string,mixed>
     */
    final protected function getDirectiveArgumentsSchemaDefinition(RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        $directiveSchemaDefinition = $this->getDirectiveSchemaDefinition($relationalTypeResolver);
        return $directiveSchemaDefinition[SchemaDefinition::ARGS] ?? [];
    }

    /**
     * Indicate to what fieldNames this directive can be applied.
     * Returning an empty array means all of them
     *
     * @return string[]
     */
    public function getFieldNamesToApplyTo(): array
    {
        // By default, apply to all fieldNames
        return [];
    }

    /**
     * By default, the directiveResolver instance can process the directive
     * This function can be overridden to force certain value on the directive args before it can be executed
     */
    public function resolveCanProcessDirective(
        RelationalTypeResolverInterface $relationalTypeResolver,
        Directive $directive,
    ): bool {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        /** Check if to validate the version */
        if (
            $moduleConfiguration->enableSemanticVersionConstraints()
            && $this->hasDirectiveVersion($relationalTypeResolver)
        ) {
            /** @var string */
            $schemaDirectiveVersion = $this->getDirectiveVersion($relationalTypeResolver);
            /**
             * Get versionConstraint in this order:
             * 1. Passed as directive argument
             * 2. Through param `directiveVersionConstraints[$directiveName]`: specific to the directive
             * 3. Through param `versionConstraint`: applies to all fields and directives in the query
             */
            $versionConstraint =
                $directive->getArgumentValue(SchemaDefinition::VERSION_CONSTRAINT)
                ?? $this->getVersioningService()->getVersionConstraintsForDirective($this)
                ?? App::getState('version-constraint');
            /**
             * If the query doesn't restrict the version, then do not process
             */
            if (!$versionConstraint) {
                return false;
            }
            /**
             * Compare using semantic versioning constraint rules, as used by Composer
             */
            return $this->getSemverHelperService()->satisfies($schemaDirectiveVersion, $versionConstraint);
        }
        return true;
    }

    final public function resolveCanProcessField(
        RelationalTypeResolverInterface $relationalTypeResolver,
        FieldInterface $field,
    ): bool {
        $directiveSupportedFieldNames = $this->getFieldNamesToApplyTo();
        if ($directiveSupportedFieldNames !== [] && !in_array($field->getName(), $directiveSupportedFieldNames)) {
            return false;
        }

        /**
         * If passing a UnionTypeResolver, simply evaluate the condition
         * in any of the targetObjectTypeResolver, expecting them to
         * have the same rules for applying the directive (or not)
         *
         * Eg:
         *
         * ```
         * {
         *   customPosts {
         *     id
         *     # This is delegated to Post to be resolved
         *     date @default(value:"1982-06-29T17:48:25+00:00")
         *   }
         * }
         * ```
         */
        if ($relationalTypeResolver instanceof UnionTypeResolverInterface) {
            /** @var UnionTypeResolverInterface */
            $unionTypeResolver = $relationalTypeResolver;
            $targetObjectTypeResolvers = $unionTypeResolver->getTargetObjectTypeResolvers();
            /**
             * There will be a GraphQL error somewhere else,
             * process the field as to avoid adding yet another error
             */
            if ($targetObjectTypeResolvers === []) {
                return true;
            }
            $targetObjectTypeResolver = $targetObjectTypeResolvers[0];
            return $this->resolveCanProcessField($targetObjectTypeResolver, $field);
        }

        /** @var ObjectTypeResolverInterface */
        $objectTypeResolver = $relationalTypeResolver;

        return $this->resolveCanProcessFieldBasedOnSupportedFieldTypeResolverClasses(
            $objectTypeResolver,
            $field,
        );
    }

    /**
     * Check if the directive only handles specific types
     */
    protected function resolveCanProcessFieldBasedOnSupportedFieldTypeResolverClasses(
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldInterface $field,
    ): bool {
        /**
         * The field type resolver may be provided via AppState or,
         * if absent, only then retrieve it from the Field.
         */
        $fieldTypeResolver = $this->getFieldTypeResolverFromAppStateOrField(
            $objectTypeResolver,
            $field,
        );

        /**
         * There will be a GraphQL error somewhere else,
         * process the field as to avoid adding yet another error
         */
        if ($fieldTypeResolver === null) {
            return true;
        }

        /**
         * DangerouslyNonSpecific Scalar is always processed
         */
        if ($fieldTypeResolver instanceof DangerouslyNonSpecificScalarTypeScalarTypeResolver) {
            return true;
        }

        $supportedFieldTypeResolverClasses = $this->getSupportedFieldTypeResolverClasses();
        if (
            $supportedFieldTypeResolverClasses !== null
            && array_filter(
                $supportedFieldTypeResolverClasses,
                fn (string $supportedFieldTypeResolverClass) => $fieldTypeResolver instanceof $supportedFieldTypeResolverClass
            ) === []
        ) {
            return false;
        }

        $excludedFieldTypeResolverClasses = $this->getExcludedFieldTypeResolverClasses();
        if (
            $excludedFieldTypeResolverClasses !== null
            && array_filter(
                $excludedFieldTypeResolverClasses,
                fn (string $excludedFieldTypeResolverClass) => $fieldTypeResolver instanceof $excludedFieldTypeResolverClass
            ) !== []
        ) {
            return false;
        }

        return true;
    }

    /**
     * Nested directives could modify the type being processed,
     * as when applied on a sub item from the field value
     * (eg: @underJSONObjectProperty has type JSONObject,
     * but the value being processed will have some other type).
     *
     * Then, either retrieve the type provided via AppState or,
     * if absent, only then retrieve it from the Field.
     */
    protected function getFieldTypeResolverFromAppStateOrField(
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldInterface $field,
    ): ?ConcreteTypeResolverInterface {
        /** @var ConcreteTypeResolverInterface|null */
        $currentSupportedDirectiveResolutionFieldTypeResolver = App::getState('field-type-resolver-for-supported-directive-resolution');
        if ($currentSupportedDirectiveResolutionFieldTypeResolver !== null) {
            return $currentSupportedDirectiveResolutionFieldTypeResolver;
        }

        return $objectTypeResolver->getFieldTypeResolver($field);
    }

    /**
     * For Field Directives: Print what types does the directive support,
     * or `null` to mean it supports them all.
     *
     * For Operation Directives: Print `null`.
     *
     * It can be a name, such as `String`, or a description,
     * such as `Any type implementing the CustomPost interface`.
     *
     * @return string[]|null
     */
    protected function getSupportedFieldTypeNamesOrDescriptions(): ?array
    {
        $fieldDirectiveBehavior = $this->getFieldDirectiveBehavior();
        if ($fieldDirectiveBehavior === FieldDirectiveBehaviors::OPERATION) {
            return null;
        }

        $supportedFieldTypeResolverContainerServiceIDs = $this->getSupportedFieldTypeResolverContainerServiceIDs();
        if ($supportedFieldTypeResolverContainerServiceIDs === null) {
            return null;
        }

        $concreteTypeResolvers = $this->getSupportedConcreteTypeResolvers($supportedFieldTypeResolverContainerServiceIDs);
        if ($concreteTypeResolvers === null) {
            return null;
        }

        return $this->getConcreteTypeResolverNamesOrDescriptions($concreteTypeResolvers);
    }

    /**
     * @param ConcreteTypeResolverInterface[] $concreteTypeResolvers
     * @return string[]
     */
    protected function getConcreteTypeResolverNamesOrDescriptions(array $concreteTypeResolvers): ?array
    {
        return array_map(
            fn (ConcreteTypeResolverInterface $typeResolver) => $typeResolver->getMaybeNamespacedTypeName(),
            $concreteTypeResolvers
        );
    }

    /**
     * @param string[] $supportedFieldTypeResolverContainerServiceIDs
     * @return ConcreteTypeResolverInterface[]|null
     */
    protected function getSupportedConcreteTypeResolvers(array $supportedFieldTypeResolverContainerServiceIDs): ?array
    {
        /** @var ConcreteTypeResolverInterface[] */
        return array_map(
            function (string $serviceID): ConcreteTypeResolverInterface {
                /** @var ConcreteTypeResolverInterface */
                return $this->instanceManager->getInstance($serviceID);
            },
            $supportedFieldTypeResolverContainerServiceIDs
        );
    }

    /**
     * @return array<class-string<ConcreteTypeResolverInterface>>|null
     */
    protected function getSupportedFieldTypeResolverContainerServiceIDs(): ?array
    {
        return $this->getSupportedFieldTypeResolverClasses();
    }

    /**
     * @return array<class-string<ConcreteTypeResolverInterface>>|null
     */
    protected function getSupportedFieldTypeResolverClasses(): ?array
    {
        return null;
    }

    /**
     * @return array<class-string<ConcreteTypeResolverInterface>>|null
     */
    protected function getExcludedFieldTypeResolverClasses(): ?array
    {
        return null;
    }

    /**
     * Validate the constraints for a directive argument
     *
     * @param FieldInterface[] $fields
     * @param array<string,mixed> $directiveArgs
     */
    protected function validateDirectiveArgValue(
        string $directiveArgName,
        mixed $directiveArgValue,
        AstInterface $astNode,
        array $directiveArgs,
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $fields,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
    }

    /**
     * By default, place the directive after the ResolveAndMerge directive,
     * so the property will be in $resolvedIDFieldValues by then
     */
    public function getPipelinePosition(): string
    {
        return PipelinePositions::AFTER_RESOLVE;
    }

    /**
     * Indicate if the directive needs to be passed $idFieldSet filled with data to be able to execute
     * Because most commonly it will need, the default value is `true`
     */
    public function needsSomeIDFieldToExecute(): bool
    {
        return true;
    }

    /**
     * Indicate that there is data in variable $idFieldSet
     *
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     */
    protected function hasSomeIDField(array $idFieldSet): bool
    {
        foreach ($idFieldSet as $id => $fieldSet) {
            if ($fieldSet->fields !== []) {
                // If there's direct-fields to fetch for any ID, that's it, there's data
                return true;
            }
        }
        // If we reached here, there is no data
        return false;
    }

    public function getDirectiveVersion(RelationalTypeResolverInterface $relationalTypeResolver): ?string
    {
        return null;
    }

    final public function hasDirectiveVersion(RelationalTypeResolverInterface $relationalTypeResolver): bool
    {
        return !empty($this->getDirectiveVersion($relationalTypeResolver));
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getDirectiveArgNameTypeResolvers(RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($relationalTypeResolver);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getDirectiveArgNameTypeResolvers($relationalTypeResolver);
        }

        $directiveArgNameTypeResolvers = [];

        /** @var GraphQLParserModuleConfiguration */
        $moduleConfiguration = App::getModule(GraphQLParserModule::class)->getConfiguration();
        if ($moduleConfiguration->enableMultiFieldDirectives()) {
            $affectAdditionalFieldsUnderPosArgumentName = $this->getAffectAdditionalFieldsUnderPosArgumentName();
            if ($affectAdditionalFieldsUnderPosArgumentName !== null) {
                $directiveArgNameTypeResolvers[$affectAdditionalFieldsUnderPosArgumentName] = $this->getIntScalarTypeResolver();
            }
        }

        return $directiveArgNameTypeResolvers;
    }

    /**
     * Name for the directive arg to indicate which additional fields
     * must be affected by the directive, by indicating their relative position.
     *
     * Eg: { posts { excerpt content @strTranslate(affectAdditionalFieldsUnderPos: [1]) } }
     *
     * @return string Name of the directiveArg, or `null` to disable this feature for the directive
     */
    public function getAffectAdditionalFieldsUnderPosArgumentName(): ?string
    {
        return 'affectAdditionalFieldsUnderPos';
    }

    public function getDirectiveArgDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): ?string
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($relationalTypeResolver);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getDirectiveArgDescription($relationalTypeResolver, $directiveArgName);
        }
        return match ($directiveArgName) {
            // Version constraint (possibly enabled)
            SchemaDefinition::VERSION_CONSTRAINT => $this->getVersionConstraintFieldOrDirectiveArgDescription(),
            // Multi-Field Directives (possibly enabled)
            $this->getAffectAdditionalFieldsUnderPosArgumentName() => $this->__('Positions of the additional fields to be affected by the directive, relative from the directive (as an array of positive integers)', 'graphql-server'),
            default => null,
        };
    }

    public function getDirectiveArgDefaultValue(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): mixed
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($relationalTypeResolver);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getDirectiveArgDefaultValue($relationalTypeResolver, $directiveArgName);
        }
        return match ($directiveArgName) {
            $this->getAffectAdditionalFieldsUnderPosArgumentName() => [],
            default => null,
        };
    }

    public function getDirectiveArgTypeModifiers(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): int
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($relationalTypeResolver);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getDirectiveArgTypeModifiers($relationalTypeResolver, $directiveArgName);
        }
        return match ($directiveArgName) {
            $this->getAffectAdditionalFieldsUnderPosArgumentName() => SchemaTypeModifiers::MANDATORY | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default => SchemaTypeModifiers::NONE,
        };
    }

    /**
     * Consolidation of the schema directive arguments. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     */
    public function getConsolidatedDirectiveArgNameTypeResolvers(RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        // Cache the result
        $cacheKey = $relationalTypeResolver::class;
        if (array_key_exists($cacheKey, $this->consolidatedDirectiveArgNameTypeResolversCache)) {
            return $this->consolidatedDirectiveArgNameTypeResolversCache[$cacheKey];
        }

        $directiveArgNameTypeResolvers = $this->getDirectiveArgNameTypeResolvers($relationalTypeResolver);

        /**
         * Allow to override/extend the inputs (eg: module "Post Categories" can add
         * input "categories" to field "Root.createPost")
         */
        $consolidatedDirectiveArgNameTypeResolvers = App::applyFilters(
            HookNames::DIRECTIVE_ARG_NAME_TYPE_RESOLVERS,
            $directiveArgNameTypeResolvers,
            $this,
            $relationalTypeResolver
        );

        /**
         * Add the version constraint (if enabled)
         * Only add the argument if this field or directive has a version
         * If it doesn't, then there will only be one version of it,
         * and it can be kept empty for simplicity
         */
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if (
            $moduleConfiguration->enableSemanticVersionConstraints()
            && $this->hasDirectiveVersion($relationalTypeResolver)
        ) {
            /**
             * The version is always of the `String` type service, but do not
             * obtain it through method `getStringScalarTypeResolver` so that
             * this method is not declared on all extending classes.
             *
             * @var StringScalarTypeResolver
             */
            $stringScalarTypeResolver = $this->instanceManager->getInstance(StringScalarTypeResolver::class);
            $consolidatedDirectiveArgNameTypeResolvers[SchemaDefinition::VERSION_CONSTRAINT] = $stringScalarTypeResolver;
        }

        $this->consolidatedDirectiveArgNameTypeResolversCache[$cacheKey] = $consolidatedDirectiveArgNameTypeResolvers;
        return $this->consolidatedDirectiveArgNameTypeResolversCache[$cacheKey];
    }

    /**
     * Consolidation of the schema directive arguments. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     */
    public function getConsolidatedDirectiveArgDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): ?string
    {
        // Cache the result
        $cacheKey = $relationalTypeResolver::class . '(' . $directiveArgName . ':)';
        if (array_key_exists($cacheKey, $this->consolidatedDirectiveArgDescriptionCache)) {
            return $this->consolidatedDirectiveArgDescriptionCache[$cacheKey];
        }
        $this->consolidatedDirectiveArgDescriptionCache[$cacheKey] = App::applyFilters(
            HookNames::DIRECTIVE_ARG_DESCRIPTION,
            $this->getDirectiveArgDescription($relationalTypeResolver, $directiveArgName),
            $this,
            $relationalTypeResolver,
            $directiveArgName,
        );
        return $this->consolidatedDirectiveArgDescriptionCache[$cacheKey];
    }

    /**
     * Consolidation of the schema directive arguments. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     */
    public function getConsolidatedDirectiveArgDefaultValue(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): mixed
    {
        // Cache the result
        $cacheKey = $relationalTypeResolver::class . '(' . $directiveArgName . ':)';
        if (array_key_exists($cacheKey, $this->consolidatedDirectiveArgDefaultValueCache)) {
            return $this->consolidatedDirectiveArgDefaultValueCache[$cacheKey];
        }
        $this->consolidatedDirectiveArgDefaultValueCache[$cacheKey] = App::applyFilters(
            HookNames::DIRECTIVE_ARG_DEFAULT_VALUE,
            $this->getDirectiveArgDefaultValue($relationalTypeResolver, $directiveArgName),
            $this,
            $relationalTypeResolver,
            $directiveArgName,
        );
        return $this->consolidatedDirectiveArgDefaultValueCache[$cacheKey];
    }

    /**
     * Consolidation of the schema directive arguments. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     */
    public function getConsolidatedDirectiveArgTypeModifiers(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): int
    {
        // Cache the result
        $cacheKey = $relationalTypeResolver::class . '(' . $directiveArgName . ':)';
        if (array_key_exists($cacheKey, $this->consolidatedDirectiveArgTypeModifiersCache)) {
            return $this->consolidatedDirectiveArgTypeModifiersCache[$cacheKey];
        }
        $this->consolidatedDirectiveArgTypeModifiersCache[$cacheKey] = App::applyFilters(
            HookNames::DIRECTIVE_ARG_TYPE_MODIFIERS,
            $this->getDirectiveArgTypeModifiers($relationalTypeResolver, $directiveArgName),
            $this,
            $relationalTypeResolver,
            $directiveArgName,
        );
        return $this->consolidatedDirectiveArgTypeModifiersCache[$cacheKey];
    }

    /**
     * Consolidation of the schema directive arguments. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     *
     * @return array<string,mixed>
     */
    final public function getDirectiveArgsSchemaDefinition(RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        // Cache the result
        $cacheKey = $relationalTypeResolver::class;
        if (array_key_exists($cacheKey, $this->schemaDirectiveArgsCache)) {
            return $this->schemaDirectiveArgsCache[$cacheKey];
        }
        $schemaDirectiveArgs = [];
        $consolidatedDirectiveArgNameTypeResolvers = $this->getConsolidatedDirectiveArgNameTypeResolvers($relationalTypeResolver);
        foreach ($consolidatedDirectiveArgNameTypeResolvers as $directiveArgName => $directiveArgInputTypeResolver) {
            $directiveArgDescription =
                $this->getConsolidatedDirectiveArgDescription($relationalTypeResolver, $directiveArgName)
                ?? $directiveArgInputTypeResolver->getTypeDescription();
            $schemaDirectiveArgs[$directiveArgName] = $this->getFieldOrDirectiveArgTypeSchemaDefinition(
                $directiveArgName,
                $directiveArgInputTypeResolver,
                $directiveArgDescription,
                $this->getConsolidatedDirectiveArgDefaultValue($relationalTypeResolver, $directiveArgName),
                $this->getConsolidatedDirectiveArgTypeModifiers($relationalTypeResolver, $directiveArgName),
            );
            $schemaDirectiveArgs[$directiveArgName][SchemaDefinition::EXTENSIONS] = $this->getConsolidatedDirectiveArgExtensionsSchemaDefinition($relationalTypeResolver, $directiveArgName);
        }
        $this->schemaDirectiveArgsCache[$cacheKey] = $schemaDirectiveArgs;
        return $this->schemaDirectiveArgsCache[$cacheKey];
    }

    /**
     * @return array<string,mixed>
     */
    protected function getDirectiveArgExtensionsSchemaDefinition(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): array
    {
        // @todo Implement “sensitive” directive args, if needed
        return [
            SchemaDefinition::IS_SENSITIVE_DATA_ELEMENT => false,
        ];
    }

    /**
     * Consolidation of the schema directive arguments. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     *
     * @return array<string,mixed>
     */
    final protected function getConsolidatedDirectiveArgExtensionsSchemaDefinition(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): array
    {
        // Cache the result
        $cacheKey = $relationalTypeResolver::class . '(' . $directiveArgName . ':)';
        if (array_key_exists($cacheKey, $this->consolidatedDirectiveArgExtensionsCache)) {
            return $this->consolidatedDirectiveArgExtensionsCache[$cacheKey];
        }
        $this->consolidatedDirectiveArgExtensionsCache[$cacheKey] = App::applyFilters(
            HookNames::DIRECTIVE_ARG_EXTENSIONS,
            $this->getDirectiveArgExtensionsSchemaDefinition($relationalTypeResolver, $directiveArgName),
            $this,
            $relationalTypeResolver,
            $directiveArgName,
        );
        return $this->consolidatedDirectiveArgExtensionsCache[$cacheKey];
    }

    public function getDirectiveDeprecationMessage(RelationalTypeResolverInterface $relationalTypeResolver): ?string
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($relationalTypeResolver);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getDirectiveDeprecationMessage($relationalTypeResolver);
        }
        return null;
    }

    public function getDirectiveDescription(RelationalTypeResolverInterface $relationalTypeResolver): ?string
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($relationalTypeResolver);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getDirectiveDescription($relationalTypeResolver);
        }
        return null;
    }

    public function isGlobal(RelationalTypeResolverInterface $relationalTypeResolver): bool
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($relationalTypeResolver);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->isGlobal($relationalTypeResolver);
        }
        return false;
    }

    /**
     * This is the equivalent to `__invoke` in League\Pipeline\StageInterface
     *
     * @param mixed[] $payload
     * @return mixed[]
     */
    public function resolveDirectivePipelinePayload(array $payload): array
    {
        // 1. Extract the arguments from the payload
        // $pipelineIDFieldSet is an array containing all stages of the pipe
        // The one corresponding to the current stage is at the head. Take it out from there,
        // and keep passing down the rest of the array to the next stages
        list(
            /** @var RelationalTypeResolverInterface */
            $relationalTypeResolver,
            $pipelineFieldDirectiveResolvers,
            $idObjects,
            $unionTypeOutputKeyIDs,
            $previouslyResolvedIDFieldValues,
            $pipelineIDFieldSet,
            $pipelineFieldDataAccessProviders,
            $resolvedIDFieldValues,
            $messages,
            /** @var EngineIterationFeedbackStore */
            $engineIterationFeedbackStore,
        ) = DirectivePipelineUtils::extractArgumentsFromPayload($payload);

        /** @var array<array<string|int,EngineIterationFieldSet>> $pipelineIDFieldSet */
        /** @var array<FieldDataAccessProviderInterface> $pipelineFieldDataAccessProviders */
        /** @var array<string|int,SplObjectStorage<FieldInterface,mixed>> $resolvedIDFieldValues */

        // Extract the head, keep passing down the rest
        $idFieldSet = $pipelineIDFieldSet[0];
        array_shift($pipelineIDFieldSet);
        $directiveDataAccessProvider = $pipelineFieldDataAccessProviders[0];
        array_shift($pipelineFieldDataAccessProviders);

        // The $pipelineFieldDirectiveResolvers is the series of directives executed in the pipeline
        // The current stage is at the head. Remove it
        array_shift($pipelineFieldDirectiveResolvers);

        // // 2. Validate operation
        // $this->validateDirective(
        //     $relationalTypeResolver,
        //     $idFieldSet,
        //     $pipelineIDFieldSet,
        //     $pipelineFieldDataAccessProviders,
        //     $pipelineFieldDirectiveResolvers,
        //     $idObjects,
        //     $resolvedIDFieldValues,
        //     $previouslyResolvedIDFieldValues,
        //     $messages,
        // );

        // 2. Execute operation.
        // First check that if the validation took away the elements, and so the directive can't execute anymore
        // For instance, executing ?query=posts.id|title<default,translate(from:en,to:es)> will fail
        // after directive "default", so directive "translate" must not even execute
        if (!$this->needsSomeIDFieldToExecute() || $this->hasSomeIDField($idFieldSet)) {
            // If the directive resolver throws an Exception,
            // catch it and add objectErrors
            $feedbackItemResolution = null;
            $astNode = null;
            try {
                $this->resolveDirective(
                    $relationalTypeResolver,
                    $idFieldSet,
                    $directiveDataAccessProvider,
                    $pipelineFieldDirectiveResolvers,
                    $idObjects,
                    $unionTypeOutputKeyIDs,
                    $previouslyResolvedIDFieldValues,
                    $pipelineIDFieldSet,
                    $pipelineFieldDataAccessProviders,
                    $resolvedIDFieldValues,
                    $messages,
                    $engineIterationFeedbackStore,
                );
            } catch (AbstractQueryException $queryException) {
                $feedbackItemResolution = FeedbackItemResolution::fromUpstreamFeedbackItemResolution($queryException->getFeedbackItemResolution());
                $astNode = $queryException->getAstNode();
            } catch (AbstractClientException $e) {
                $feedbackItemResolution = new FeedbackItemResolution(
                    GenericFeedbackItemProvider::class,
                    GenericFeedbackItemProvider::E1,
                    [
                        $e->getMessage(),
                    ]
                );
            } catch (Exception $e) {
                /** @var ModuleConfiguration */
                $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
                if ($moduleConfiguration->logExceptionErrorMessagesAndTraces()) {
                    $engineIterationFeedbackStore->objectResolutionFeedbackStore->addLog(
                        new ObjectResolutionFeedback(
                            new FeedbackItemResolution(
                                ErrorFeedbackItemProvider::class,
                                ErrorFeedbackItemProvider::E11A,
                                [
                                    $this->directive->asQueryString(),
                                    $e->getMessage(),
                                    $e->getTraceAsString(),
                                ]
                            ),
                            $this->directive,
                            $relationalTypeResolver,
                            $this->directive,
                            $idFieldSet,
                        )
                    );
                }
                $feedbackItemResolution = $moduleConfiguration->sendExceptionErrorMessages()
                    ? ($moduleConfiguration->sendExceptionTraces()
                        ? new FeedbackItemResolution(
                            ErrorFeedbackItemProvider::class,
                            ErrorFeedbackItemProvider::E11A,
                            [
                                $this->directive->asQueryString(),
                                $e->getMessage(),
                                $e->getTraceAsString(),
                            ]
                        )
                        : new FeedbackItemResolution(
                            ErrorFeedbackItemProvider::class,
                            ErrorFeedbackItemProvider::E11,
                            [
                                $this->directive->asQueryString(),
                                $e->getMessage(),
                            ]
                        )
                    )
                    : new FeedbackItemResolution(
                        ErrorFeedbackItemProvider::class,
                        ErrorFeedbackItemProvider::E12,
                        [
                            $this->directive->asQueryString(),
                        ]
                    );
            }
            if ($feedbackItemResolution !== null) {
                $this->processObjectFailure(
                    $relationalTypeResolver,
                    $feedbackItemResolution,
                    $idFieldSet,
                    $pipelineIDFieldSet,
                    $astNode ?? $this->directive,
                    $resolvedIDFieldValues,
                    $engineIterationFeedbackStore,
                );
            }
        }

        // 3. Re-create the payload from the modified variables
        return DirectivePipelineUtils::convertArgumentsToPayload(
            $relationalTypeResolver,
            $pipelineFieldDirectiveResolvers,
            $idObjects,
            $unionTypeOutputKeyIDs,
            $previouslyResolvedIDFieldValues,
            $pipelineIDFieldSet,
            $pipelineFieldDataAccessProviders,
            $resolvedIDFieldValues,
            $messages,
            $engineIterationFeedbackStore,
        );
    }

    /**
     * @param array<string|int,EngineIterationFieldSet> $idFieldSetToRemove
     * @param array<array<string|int,EngineIterationFieldSet>> $succeedingPipelineIDFieldSet
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $resolvedIDFieldValues
     */
    protected function processObjectFailure(
        RelationalTypeResolverInterface $relationalTypeResolver,
        FeedbackItemResolution $feedbackItemResolution,
        array $idFieldSetToRemove,
        array &$succeedingPipelineIDFieldSet,
        AstInterface $astNode,
        array &$resolvedIDFieldValues,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): void {
        $this->processFailure(
            $relationalTypeResolver,
            $feedbackItemResolution,
            $idFieldSetToRemove,
            $succeedingPipelineIDFieldSet,
            $astNode,
            $resolvedIDFieldValues,
            $engineIterationFeedbackStore->objectResolutionFeedbackStore,
        );
    }

    /**
     * Depending on environment configuration, either show a warning,
     * or show an error and remove the fields from the directive pipeline for further execution
     *
     * @param array<string|int,EngineIterationFieldSet> $idFieldSetToRemove
     * @param array<array<string|int,EngineIterationFieldSet>> $succeedingPipelineIDFieldSet
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $resolvedIDFieldValues
     */
    private function processFailure(
        RelationalTypeResolverInterface $relationalTypeResolver,
        FeedbackItemResolution $feedbackItemResolution,
        array $idFieldSetToRemove,
        array &$succeedingPipelineIDFieldSet,
        AstInterface $astNode,
        array &$resolvedIDFieldValues,
        ObjectResolutionFeedbackStore|SchemaFeedbackStore $schemaOrObjectResolutionFeedbackStore,
    ): void {
        /**
         * Remove the fields from the directive pipeline
         */
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if ($moduleConfiguration->setFieldAsNullIfDirectiveFailed()) {
            $this->removeIDFieldSet(
                $succeedingPipelineIDFieldSet,
                $idFieldSetToRemove,
            );
            $this->setFieldResponseValueAsNull(
                $resolvedIDFieldValues,
                $idFieldSetToRemove,
            );
        }

        if ($schemaOrObjectResolutionFeedbackStore instanceof SchemaFeedbackStore) {
            /** @var SchemaFeedbackStore */
            $schemaFeedbackStore = $schemaOrObjectResolutionFeedbackStore;
            $schemaFeedbackStore->addError(
                new SchemaFeedback(
                    $feedbackItemResolution,
                    $astNode,
                    $relationalTypeResolver,
                    MethodHelpers::getFieldsFromIDFieldSet($idFieldSetToRemove)
                )
            );
            return;
        }

        /** @var ObjectResolutionFeedbackStore */
        $objectResolutionFeedbackStore = $schemaOrObjectResolutionFeedbackStore;
        $objectResolutionFeedbackStore->addError(
            new ObjectResolutionFeedback(
                $feedbackItemResolution,
                $astNode,
                $relationalTypeResolver,
                $this->directive,
                $idFieldSetToRemove
            )
        );
    }

    /**
     * @param array<string|int,EngineIterationFieldSet> $idFieldSetToRemove
     * @param array<array<string|int,EngineIterationFieldSet>> $succeedingPipelineIDFieldSet
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $resolvedIDFieldValues
     */
    protected function processSchemaFailure(
        RelationalTypeResolverInterface $relationalTypeResolver,
        FeedbackItemResolution $feedbackItemResolution,
        array $idFieldSetToRemove,
        array &$succeedingPipelineIDFieldSet,
        AstInterface $astNode,
        array &$resolvedIDFieldValues,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): void {
        $this->processFailure(
            $relationalTypeResolver,
            $feedbackItemResolution,
            $idFieldSetToRemove,
            $succeedingPipelineIDFieldSet,
            $astNode,
            $resolvedIDFieldValues,
            $engineIterationFeedbackStore->schemaFeedbackStore,
        );
    }

    /**
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $resolvedIDFieldValues
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     */
    protected function setFieldResponseValueAsNull(
        array &$resolvedIDFieldValues,
        array $idFieldSet,
    ): void {
        foreach ($idFieldSet as $id => $fieldSet) {
            foreach ($fieldSet->fields as $field) {
                $resolvedIDFieldValues[$id][$field] = null;
            }
        }
    }

    /**
     * Return the object implementing the schema definition for this DirectiveResolver.
     * By default, it is this same object
     */
    protected function getSchemaDefinitionResolver(RelationalTypeResolverInterface $relationalTypeResolver): SchemaFieldDirectiveResolverInterface
    {
        return $this;
    }

    /**
     * Directives may not be directly visible in the schema,
     * eg: because their name is duplicated across directives (eg: "cacheControl")
     * or because they are used through code (eg: "validateIsUserLoggedIn")
     */
    public function skipExposingDirectiveInSchema(RelationalTypeResolverInterface $relationalTypeResolver): bool
    {
        /**
         * Do not expose the versioned directives via introspection
         */
        if ($this->hasDirectiveVersion($relationalTypeResolver)) {
            return true;
        }

        /**
         * `DangerouslyNonSpecificScalar` is a special scalar type which is not coerced or validated.
         * In particular, it does not need to validate if it is an array or not,
         * as according to the applied WrappingType.
         *
         * If disabled, then do not expose the directive if it
         * has any mandatory argument of type `DangerouslyNonSpecificScalar`
         */
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if ($moduleConfiguration->skipExposingDangerouslyNonSpecificScalarTypeTypeInSchema()) {
            /**
             * If `DangerouslyNonSpecificScalar` is disabled, do not expose the field if either:
             *
             *   1. its type is `DangerouslyNonSpecificScalar`
             *   2. it has any mandatory argument of type `DangerouslyNonSpecificScalar`
             */
            $consolidatedDirectiveArgNameTypeResolvers = $this->getConsolidatedDirectiveArgNameTypeResolvers($relationalTypeResolver);
            $consolidatedDirectiveArgsTypeModifiers = [];
            foreach (array_keys($consolidatedDirectiveArgNameTypeResolvers) as $directiveArgName) {
                $consolidatedDirectiveArgsTypeModifiers[$directiveArgName] = $this->getConsolidatedDirectiveArgTypeModifiers($relationalTypeResolver, $directiveArgName);
            }
            if (
                $this->hasMandatoryDangerouslyNonSpecificScalarTypeInputType(
                    $consolidatedDirectiveArgNameTypeResolvers,
                    $consolidatedDirectiveArgsTypeModifiers,
                )
            ) {
                return true;
            }
        }
        return false;
    }

    /**
     * Directives args may not be directly visible in the schema
     */
    public function skipExposingDirectiveArgInSchema(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): bool
    {
        return false;
    }

    /**
     * @return array<string,mixed>
     */
    final public function getDirectiveSchemaDefinition(RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        // First check if the value was cached
        $key = $relationalTypeResolver->getNamespacedTypeName();
        if (!isset($this->schemaDefinitionForDirectiveCache[$key])) {
            $this->schemaDefinitionForDirectiveCache[$key] = $this->doGetDirectiveSchemaDefinition($relationalTypeResolver);
        }
        return $this->schemaDefinitionForDirectiveCache[$key];
    }

    /**
     * @return array<string,mixed>
     */
    private function doGetDirectiveSchemaDefinition(RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        $directiveName = $this->getDirectiveName();
        $schemaDefinition = [
            SchemaDefinition::NAME => $directiveName,
            SchemaDefinition::DIRECTIVE_KIND => $this->getDirectiveKind(),
            SchemaDefinition::DIRECTIVE_LOCATIONS => $this->getDirectiveLocations(),
            SchemaDefinition::DIRECTIVE_IS_REPEATABLE => $this->isRepeatable(),
            SchemaDefinition::DIRECTIVE_IS_GLOBAL => $this->isGlobal($relationalTypeResolver),
        ];
        if ($limitedToFields = $this->getFieldNamesToApplyTo()) {
            $schemaDefinition[SchemaDefinition::DIRECTIVE_LIMITED_TO_FIELDS] = $limitedToFields;
        }
        if ($description = $this->getDirectiveDescription($relationalTypeResolver)) {
            $schemaDefinition[SchemaDefinition::DESCRIPTION] = $description;
        }
        if ($deprecationMessage = $this->getDirectiveDeprecationMessage($relationalTypeResolver)) {
            $schemaDefinition[SchemaDefinition::DEPRECATED] = true;
            $schemaDefinition[SchemaDefinition::DEPRECATION_MESSAGE] = $deprecationMessage;
        }
        if ($args = $this->getDirectiveArgsSchemaDefinition($relationalTypeResolver)) {
            $schemaDefinition[SchemaDefinition::ARGS] = $args;
        }
        /**
         * Please notice: the version always comes from the directiveResolver, and not from the schemaDefinitionResolver
         * That is because it is the implementer the one who knows what version it is, and not the one defining the interface
         * If the interface changes, the implementer will need to change, so the version will be upgraded
         * But it could also be that the contract doesn't change, but the implementation changes
         * it's really not their responsibility
         */
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if (
            $moduleConfiguration->enableSemanticVersionConstraints()
            && $this->hasDirectiveVersion($relationalTypeResolver)
        ) {
            $schemaDefinition[SchemaDefinition::VERSION] = $this->getDirectiveVersion($relationalTypeResolver);
        }
        $schemaDefinition[SchemaDefinition::EXTENSIONS] = $this->getDirectiveExtensionsSchemaDefinition($relationalTypeResolver);
        return $schemaDefinition;
    }

    /**
     * @return array<string,mixed>
     */
    public function getDirectiveExtensionsSchemaDefinition(RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        return [
            // @todo Implement “sensitive” directive, if needed
            SchemaDefinition::IS_SENSITIVE_DATA_ELEMENT => false,
            SchemaDefinition::DIRECTIVE_PIPELINE_POSITION => $this->getPipelinePosition(),
            SchemaDefinition::DIRECTIVE_NEEDS_DATA_TO_EXECUTE => $this->needsSomeIDFieldToExecute(),
            SchemaDefinition::FIELD_DIRECTIVE_SUPPORTED_TYPE_NAMES_OR_DESCRIPTIONS => $this->getSupportedFieldTypeNamesOrDescriptions(),
        ];
    }

    /**
     * Directives can be either of type "Schema" or "Query" and,
     * depending on one case or the other, might be exposed to the user.
     * By default, use the Query type
     */
    public function getDirectiveKind(): string
    {
        return DirectiveKinds::QUERY;
    }

    /**
     * The FieldDirectiveResolver handles both Field and Query/Mutation
     * Directives. Retrieve the corresponding Directive Locations,
     * as defined by the GraphQL spec.
     *
     * @return string[]
     *
     * @see https://spec.graphql.org/draft/#DirectiveLocation
     */
    public function getDirectiveLocations(): array
    {
        $directiveLocations = [];
        $fieldDirectiveBehavior = $this->getFieldDirectiveBehavior();
        $directiveKind = $this->getDirectiveKind();
        $isQueryTypeDirective = $this->isQueryTypeDirective();

        /**
         * Add the "Operation" Directive Locations
         */
        if (
            in_array($fieldDirectiveBehavior, [
            FieldDirectiveBehaviors::OPERATION,
            FieldDirectiveBehaviors::FIELD_AND_OPERATION,
            ])
        ) {
            if ($isQueryTypeDirective) {
                $directiveLocations = [
                    DirectiveLocations::QUERY,
                    DirectiveLocations::MUTATION,
                ];
            }
        }

        /**
         * Add the "Field" Directive Locations
         */
        if (
            in_array($fieldDirectiveBehavior, [
            FieldDirectiveBehaviors::FIELD,
            FieldDirectiveBehaviors::FIELD_AND_OPERATION,
            ])
        ) {
            if ($isQueryTypeDirective) {
                /**
                 * Same DirectiveLocations as used by `@skip`
                 *
                 * @see https://graphql.github.io/graphql-spec/draft/#sec--skip
                 */
                $directiveLocations = [
                    ...$directiveLocations,
                    DirectiveLocations::FIELD,
                    DirectiveLocations::FRAGMENT_SPREAD,
                    DirectiveLocations::INLINE_FRAGMENT,
                ];
            } elseif ($directiveKind === DirectiveKinds::SCHEMA) {
                /** @var ModuleConfiguration */
                $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
                if ($moduleConfiguration->exposeSchemaTypeDirectiveLocations()) {
                    $directiveLocations = [
                        ...$directiveLocations,
                        DirectiveLocations::FIELD_DEFINITION,
                    ];
                }
            }
        }

        return $directiveLocations;
    }

    /**
     * A "query-type" directive, as defined by the GraphQL spec,
     * must be exposed to the client.
     *
     * Non-query-type directives include the "schema-type"
     * directive, also defined in the GraphQL spec,
     * and also the "system" directives, which are internal
     * directives to this GraphQL server, such as @resolveValueAndMerge.
     *
     * There are 3 cases for the directive being considered
     * of "Query" type:
     *
     *   1. When the type is "Query"
     *   2. When the type is "Schema" and we are editing the query on the back-end
     *      (as to replace the lack of SDL)
     *   3. When the type is "Indexing" and composable directives are enabled
     */
    protected function isQueryTypeDirective(): bool
    {
        /** @var GraphQLParserModuleConfiguration */
        $graphQLParserModuleConfiguration = App::getModule(GraphQLParserModule::class)->getConfiguration();

        /** @var ModuleConfiguration */
        $componentModelModuleConfiguration = App::getModule(Module::class)->getConfiguration();

        $directiveKind = $this->getDirectiveKind();
        return $directiveKind === DirectiveKinds::QUERY
            || ($directiveKind === DirectiveKinds::SCHEMA && $componentModelModuleConfiguration->includeSchemaTypeDirectivesInSchema())
            || ($directiveKind === DirectiveKinds::INDEXING && $graphQLParserModuleConfiguration->enableComposableDirectives());
    }

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
    public function getFieldDirectiveBehavior(): string
    {
        return FieldDirectiveBehaviors::FIELD;
    }

    /**
     * By default, a directive can be executed only one time for "Schema" and "System"
     * type directives (eg: <translate(en,es),translate(es,en)>),
     * and many times for the other types, "Query", "Scripting" and "Indexing"
     */
    public function isRepeatable(): bool
    {
        return !($this->getDirectiveKind() === DirectiveKinds::SYSTEM || $this->getDirectiveKind() === DirectiveKinds::SCHEMA);
    }
}
