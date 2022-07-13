<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

use Exception;
use PoP\ComponentModel\AttachableExtensions\AttachableExtensionManagerInterface;
use PoP\ComponentModel\AttachableExtensions\AttachableExtensionTrait;
use PoP\ComponentModel\DirectivePipeline\DirectivePipelineUtils;
use PoP\ComponentModel\Directives\DirectiveKinds;
use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\Environment;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\Feedback\ObjectResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\Feedback\SchemaFeedback;
use PoP\ComponentModel\FeedbackItemProviders\ErrorFeedbackItemProvider;
use PoP\ComponentModel\FeedbackItemProviders\WarningFeedbackItemProvider;
use PoP\ComponentModel\HelperServices\SemverHelperServiceInterface;
use PoP\ComponentModel\Module;
use PoP\ComponentModel\ModuleConfiguration;
use PoP\ComponentModel\QueryResolution\FieldDataAccessProviderInterface;
use PoP\ComponentModel\Resolvers\CheckDangerouslyNonSpecificScalarTypeFieldOrDirectiveResolverTrait;
use PoP\ComponentModel\Resolvers\FieldOrDirectiveResolverTrait;
use PoP\ComponentModel\Resolvers\ObjectTypeOrDirectiveResolverTrait;
use PoP\ComponentModel\Resolvers\ResolverTypes;
use PoP\ComponentModel\Resolvers\WithVersionConstraintFieldOrDirectiveResolverTrait;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\PipelinePositions;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\DangerouslyNonSpecificScalarTypeScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoP\ComponentModel\Versioning\VersioningServiceInterface;
use PoP\GraphQLParser\Exception\Parser\InvalidDynamicContextException;
use PoP\GraphQLParser\Module as GraphQLParserModule;
use PoP\GraphQLParser\ModuleConfiguration as GraphQLParserModuleConfiguration;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;
use PoP\Root\App;
use PoP\Root\Exception\AbstractClientException;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\Root\FeedbackItemProviders\GenericFeedbackItemProvider;
use PoP\Root\Services\BasicServiceTrait;
use SplObjectStorage;

abstract class AbstractDirectiveResolver implements DirectiveResolverInterface
{
    use AttachableExtensionTrait;
    use RemoveIDFieldSetDirectiveResolverTrait;
    use FieldOrDirectiveResolverTrait;
    use WithVersionConstraintFieldOrDirectiveResolverTrait;
    use BasicServiceTrait;
    use CheckDangerouslyNonSpecificScalarTypeFieldOrDirectiveResolverTrait;
    use ObjectTypeOrDirectiveResolverTrait;

    private const MESSAGE_EXPRESSIONS_FOR_OBJECT = 'expressionsForObject';
    private const MESSAGE_EXPRESSIONS_FOR_OBJECT_AND_FIELD = 'expressionsForObjectAndField';

    protected Directive $directive;
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
     * @var array<string,mixed>
     */
    protected ?array $directiveData = null;
    
    /**
     * @var array<string,mixed>
     */
    protected array $directiveArgsForSchema = [];
    /**
     * @var array<string,mixed>
     */
    protected array $directiveArgsForObjects = [];

    /**
     * @var array<string,array>
     */
    protected array $schemaDefinitionForDirectiveCache = [];

    private ?FieldQueryInterpreterInterface $fieldQueryInterpreter = null;
    private ?SemverHelperServiceInterface $semverHelperService = null;
    private ?AttachableExtensionManagerInterface $attachableExtensionManager = null;
    private ?DangerouslyNonSpecificScalarTypeScalarTypeResolver $dangerouslyNonSpecificScalarTypeScalarTypeResolver = null;
    private ?VersioningServiceInterface $versioningService = null;
    private ?IntScalarTypeResolver $intScalarTypeResolver = null;

    final public function setFieldQueryInterpreter(FieldQueryInterpreterInterface $fieldQueryInterpreter): void
    {
        $this->fieldQueryInterpreter = $fieldQueryInterpreter;
    }
    final protected function getFieldQueryInterpreter(): FieldQueryInterpreterInterface
    {
        return $this->fieldQueryInterpreter ??= $this->instanceManager->getInstance(FieldQueryInterpreterInterface::class);
    }
    final public function setSemverHelperService(SemverHelperServiceInterface $semverHelperService): void
    {
        $this->semverHelperService = $semverHelperService;
    }
    final protected function getSemverHelperService(): SemverHelperServiceInterface
    {
        return $this->semverHelperService ??= $this->instanceManager->getInstance(SemverHelperServiceInterface::class);
    }
    final public function setAttachableExtensionManager(AttachableExtensionManagerInterface $attachableExtensionManager): void
    {
        $this->attachableExtensionManager = $attachableExtensionManager;
    }
    final protected function getAttachableExtensionManager(): AttachableExtensionManagerInterface
    {
        return $this->attachableExtensionManager ??= $this->instanceManager->getInstance(AttachableExtensionManagerInterface::class);
    }
    final public function setDangerouslyNonSpecificScalarTypeScalarTypeResolver(DangerouslyNonSpecificScalarTypeScalarTypeResolver $dangerouslyNonSpecificScalarTypeScalarTypeResolver): void
    {
        $this->dangerouslyNonSpecificScalarTypeScalarTypeResolver = $dangerouslyNonSpecificScalarTypeScalarTypeResolver;
    }
    final protected function getDangerouslyNonSpecificScalarTypeScalarTypeResolver(): DangerouslyNonSpecificScalarTypeScalarTypeResolver
    {
        return $this->dangerouslyNonSpecificScalarTypeScalarTypeResolver ??= $this->instanceManager->getInstance(DangerouslyNonSpecificScalarTypeScalarTypeResolver::class);
    }
    final public function setVersioningService(VersioningServiceInterface $versioningService): void
    {
        $this->versioningService = $versioningService;
    }
    final protected function getVersioningService(): VersioningServiceInterface
    {
        return $this->versioningService ??= $this->instanceManager->getInstance(VersioningServiceInterface::class);
    }
    final public function setIntScalarTypeResolver(IntScalarTypeResolver $intScalarTypeResolver): void
    {
        $this->intScalarTypeResolver = $intScalarTypeResolver;
    }
    final protected function getIntScalarTypeResolver(): IntScalarTypeResolver
    {
        return $this->intScalarTypeResolver ??= $this->instanceManager->getInstance(IntScalarTypeResolver::class);
    }

    /**
     * The directiveResolvers are instantiated through the service container,
     * but NOT for the directivePipeline, since there each directiveResolver
     * will require the actual $directive to process.
     *
     * By default, the directive is directly the directive name.
     * This is what is used when instantiating the directive through the container.
     */
    public function __construct()
    {
        $this->directive = new Directive(
            $this->getDirectiveName(),
            [],
            LocationHelper::getNonSpecificLocation()
        );
    }

    /**
     * Invoked when creating the non-shared directive instance
     * to resolve a field in the pipeline
     */
    final public function setDirective(Directive $directive): void
    {
        $this->directive = $directive;
    }

    public function getDirective(): Directive
    {
        return $this->directive;
    }

    final public function getClassesToAttachTo(): array
    {
        return $this->getRelationalTypeOrInterfaceTypeResolverClassesToAttachTo();
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
     * If a directive does not operate over the objects, then it must not allow to add fields or dynamic values in the directive arguments
     * Otherwise, it can lead to errors, since the field would never be transformed/casted to the expected type
     * Eg: <cacheControl(maxAge:id())>
     */
    protected function disableDynamicFieldsFromDirectiveArgs(): bool
    {
        return false;
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
        // @todo Remove commented line
        // $this->integrateDefaultDirectiveArguments($relationalTypeResolver);
        $this->directiveData = $this->getDirectiveData(
            $relationalTypeResolver,
            $fields,
            $engineIterationFeedbackStore,
        );
    }

    /**
     * Extract the FieldArgs into its corresponding DirectiveDataAccessor, which integrates
     * within the default values and coerces them according to the schema.
     *
     * @param FieldInterface[] $fields
     * @return array<string,mixed>|null Null if there was an error validating the directive
     */
    protected function getDirectiveData(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $fields,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): ?array {
        try {
            $directiveData = $this->directive->getArgumentKeyValues();
        } catch (InvalidDynamicContextException $invalidDynamicContextException) {
            $dynamicVariableReference = $invalidDynamicContextException->getDynamicVariableReference();
            $engineIterationFeedbackStore->schemaFeedbackStore->addError(
                new SchemaFeedback(
                    new FeedbackItemResolution(
                        ErrorFeedbackItemProvider::class,
                        ErrorFeedbackItemProvider::E31,
                        [
                            $dynamicVariableReference->getName(),
                            $invalidDynamicContextException->getMessage(),
                        ]
                    ),
                    $dynamicVariableReference,
                    $relationalTypeResolver,
                    $fields,
                )
            );
            return null;
        }

        $directiveArgsSchemaDefinition = $this->getDirectiveArgumentsSchemaDefinition($relationalTypeResolver);

        /**
         * Add the default Argument values
         */
        $directiveArgumentNameDefaultValues = $this->getFieldOrDirectiveArgumentNameDefaultValues($directiveArgsSchemaDefinition);
        $directiveData = $this->addDefaultFieldOrDirectiveArguments(
            $directiveData,
            $directiveArgumentNameDefaultValues,
        );

        /**
         * Cast the Arguments, return if any of them produced an error
         */
        $separateObjectTypeFieldResolutionFeedbackStore = new ObjectTypeFieldResolutionFeedbackStore();
        $directiveData = $this->getSchemaCastingService()->castArguments(
            $directiveData,
            $directiveArgsSchemaDefinition,
            $field,
            $separateObjectTypeFieldResolutionFeedbackStore,
        );
        $objectTypeFieldResolutionFeedbackStore->incorporate($separateObjectTypeFieldResolutionFeedbackStore);
        if ($separateObjectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
            return null;
        }

        /**
         * Allow to inject additional arguments
         */
        $directiveData = $objectTypeFieldResolver->prepareFieldData(
            $directiveData,
            $this,
            $field,
            $objectTypeFieldResolutionFeedbackStore
        );
        if ($directiveData === null) {
            return null;
        }

        /**
         * Perform validations
         */
        $this->validateFieldData(
            $directiveData,
            $field,
            !$objectTypeFieldResolver->validateMutationOnObject($this, $this->directive->getName()),
            $objectTypeFieldResolutionFeedbackStore,
        );
        if ($objectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
            return null;
        }

        return $directiveData;
    }

    /**
     * Validate the field data
     *
     * @param array<string,mixed> $directiveData
     */
    protected function validateFieldData(
        array $directiveData,
        FieldInterface $field,
        bool $validateMutation,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): void {
        /** @var array */
        $directiveArgsSchemaDefinition = $this->getFieldArgumentsSchemaDefinition($field);
        /** @var ObjectTypeFieldResolverInterface */
        $objectTypeFieldResolver = $this->getExecutableObjectTypeFieldResolverForField($field);

        // Collect the deprecations from the queried fields
        $objectTypeFieldResolver->collectFieldValidationDeprecationMessages($this, $field, $objectTypeFieldResolutionFeedbackStore);

        /**
         * Validations:
         *
         * - no mandatory arg is missing
         * - no non-existing arg has been provided
         */
        $separateObjectTypeFieldResolutionFeedbackStore = new ObjectTypeFieldResolutionFeedbackStore();
        $this->validateNonMissingMandatoryFieldArguments(
            $directiveData,
            $directiveArgsSchemaDefinition,
            $field,
            $separateObjectTypeFieldResolutionFeedbackStore
        );
        $this->validateOnlyExistingFieldArguments(
            $directiveData,
            $directiveArgsSchemaDefinition,
            $field,
            $separateObjectTypeFieldResolutionFeedbackStore
        );
        $objectTypeFieldResolutionFeedbackStore->incorporate($separateObjectTypeFieldResolutionFeedbackStore);
        if ($separateObjectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
            return;
        }

        /**
         * Validations:
         *
         * - constraints of the arguments
         * - custom constraints of the arguments set by the field resolver
         * - mutation custom validations
         */
        $directiveDataAccessor = $this->createFieldDataAccessor(
            $field,
            $directiveData,
        );
        $this->validateFieldArgumentConstraints(
            $directiveData,
            $objectTypeFieldResolver,
            $field,
            $objectTypeFieldResolutionFeedbackStore,
        );
        $objectTypeFieldResolver->validateFieldKeyValues(
            $this,
            $directiveDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        /**
         * If a MutationResolver is declared, let it validate the schema
         */
        $mutationResolver = $objectTypeFieldResolver->getFieldMutationResolver($this, $this->directive->getName());
        if ($mutationResolver !== null && $validateMutation) {
            $directiveDataAccessorForMutation = $this->getFieldDataAccessorForMutation($directiveDataAccessor);
            $mutationResolver->validateErrors($directiveDataAccessorForMutation, $objectTypeFieldResolutionFeedbackStore);
        }
    }

    /**
     * Validate that if the key is missing or is `null`,
     * but not if the value is empty such as '""' or [],
     * because empty values could be allowed.
     *
     * Eg: `setTagsOnPost(tags:[])` where `tags` is mandatory
     *
     * @param array<string,mixed> $directiveArgsSchemaDefinition
     */
    private function validateNonMissingMandatoryFieldArguments(
        array $directiveData,
        array $directiveArgsSchemaDefinition,
        FieldInterface $field,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): void {
        $mandatoryFieldArgNames = $this->getFieldOrDirectiveMandatoryArgumentNames($directiveArgsSchemaDefinition);
        $missingMandatoryFieldArgNames = array_values(array_filter(
            $mandatoryFieldArgNames,
            fn (string $fieldArgName) => ($directiveData[$fieldArgName] ?? null) === null
        ));
        if ($missingMandatoryFieldArgNames !== []) {
            $engineIterationFeedbackStore->schemaFeedbackStore->addError(
                new SchemaFeedback(
                    new FeedbackItemResolution(
                        ErrorFeedbackItemProvider::class,
                        ErrorFeedbackItemProvider::E29,
                        [
                            count($missingMandatoryFieldArgNames) === 1
                                ? $missingMandatoryFieldArgNames[0]
                                : implode($this->getTranslationAPI()->__('\', \''), $missingMandatoryFieldArgNames),
                            $this->directive->getName(),
                            $this->getMaybeNamespacedTypeName(),
                        ]
                    ),
                    $field,
                )
            );
        }
    }

    /**
     * Return an error if the query contains argument(s) that
     * does not exist in the field.
     *
     * @param array<string,mixed> $directiveArgsSchemaDefinition
     */
    private function validateOnlyExistingFieldArguments(
        array $directiveData,
        array $directiveArgsSchemaDefinition,
        FieldInterface $field,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): void {
        $nonExistingArgNames = array_values(array_diff(
            array_keys($directiveData),
            array_keys($directiveArgsSchemaDefinition)
        ));
        foreach ($nonExistingArgNames as $nonExistingArgName) {
            $engineIterationFeedbackStore->schemaFeedbackStore->addError(
                new SchemaFeedback(
                    new FeedbackItemResolution(
                        ErrorFeedbackItemProvider::class,
                        ErrorFeedbackItemProvider::E27,
                        [
                            $this->directive->getName(),
                            $this->getMaybeNamespacedTypeName(),
                            $nonExistingArgName,
                        ]
                    ),
                    $field->getArgument($nonExistingArgName) ?? $field,
                )
            );
        }
    }

    /**
     * Validate the constraints for the field arguments
     */
    private function validateFieldArgumentConstraints(
        array $directiveData,
        ObjectTypeFieldResolverInterface $objectTypeFieldResolver,
        FieldInterface $field,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): void {
        $fieldArgNameTypeResolvers = $objectTypeFieldResolver->getConsolidatedFieldArgNameTypeResolvers($this, $this->directive->getName());
        foreach ($directiveData as $argName => $argValue) {
            $fieldArgTypeResolver = $fieldArgNameTypeResolvers[$argName];
            $astNode = $field->getArgument($argName) ?? $field;
            /**
             * If the field is an InputObject, let it perform validations on its input fields.
             */
            if ($fieldArgTypeResolver instanceof InputObjectTypeResolverInterface) {
                $fieldArgTypeResolver->validateInputValue(
                    $argValue,
                    $astNode,
                    $objectTypeFieldResolutionFeedbackStore,
                );
            }
            $objectTypeFieldResolver->validateFieldArgValue(
                $this,
                $this->directive->getName(),
                $argName,
                $argValue,
                $astNode,
                $objectTypeFieldResolutionFeedbackStore,
            );
        }
    }

    final protected function integrateDefaultDirectiveArguments(RelationalTypeResolverInterface $relationalTypeResolver): void
    {
        $directiveArgumentNameDefaultValues = $this->getDirectiveArgumentNameDefaultValues($relationalTypeResolver);
        if ($directiveArgumentNameDefaultValues === null) {
            return;
        }
        // @todo Fix integrate with Directive
        $this->deprecatedIntegrateDefaultFieldOrDirectiveArguments(
            $this->directive,
            $directiveArgumentNameDefaultValues,
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

    final protected function getDirectiveArgumentsSchemaDefinition(RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        $directiveSchemaDefinition = $this->getDirectiveSchemaDefinition($relationalTypeResolver);
        return $directiveSchemaDefinition[SchemaDefinition::ARGS] ?? [];
    }

    /**
     * Extract and validate the directive arguments
     *
     * @param SplObjectStorage<Directive,FieldInterface[]> $directiveFields
     */
    public function dissectAndValidateDirectiveForSchema(
        RelationalTypeResolverInterface $relationalTypeResolver,
        SplObjectStorage $directiveFields,
        array &$variables,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): array {
        // First validate schema (eg of error in schema: ?query=posts<include(if:this-field-doesnt-exist())>)
        list(
            $validDirective,
            $directiveName,
            $directiveArgs,
        ) = $this->getFieldQueryInterpreter()->extractDirectiveArgumentsForSchema(
            $this,
            $relationalTypeResolver,
            $this->directive,
            $directiveFields,
            $variables,
            $engineIterationFeedbackStore,
            $this->disableDynamicFieldsFromDirectiveArgs()
        );

        // Store the args, they may be used in `resolveDirective`
        if ($directiveArgs !== null) {
            $this->directiveArgsForSchema = $directiveArgs;
        }

        return [
            $validDirective,
            $directiveName,
            $directiveArgs,
        ];
    }

    /**
     * By default, validate if there are deprecated fields
     *
     * @param FieldInterface[] $fields
     */
    public function validateDirectiveArgumentsForSchema(
        RelationalTypeResolverInterface $relationalTypeResolver,
        string $directiveName,
        array $directiveArgs,
        array $fields,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): array {
        $deprecationFeedbackItemResolutions = $this->resolveDirectiveValidationDeprecations(
            $relationalTypeResolver,
            $directiveName,
            $directiveArgs
        );
        foreach ($deprecationFeedbackItemResolutions as $deprecationFeedbackItemResolution) {
            $engineIterationFeedbackStore->schemaFeedbackStore->addDeprecation(
                new SchemaFeedback(
                    $deprecationFeedbackItemResolution,
                    $this->directive,
                    $relationalTypeResolver,
                    $fields,
                )
            );
        }
        return $directiveArgs;
    }

    /**
     * @param FieldInterface[] $fields
     */
    public function dissectAndValidateDirectiveForObject(
        RelationalTypeResolverInterface $relationalTypeResolver,
        object $object,
        array $fields,
        array &$variables,
        array &$expressions,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): array {
        list(
            $validDirective,
            $directiveName,
            $directiveArgs,
        ) = $this->getFieldQueryInterpreter()->extractDirectiveArgumentsForObject(
            $this,
            $relationalTypeResolver,
            $object,
            $fields,
            $this->directive,
            $variables,
            $expressions,
            $engineIterationFeedbackStore
        );

        // Store the args, they may be used in `resolveDirective`
        $objectID = $relationalTypeResolver->getID($object);
        $this->directiveArgsForObjects[$objectID] = $directiveArgs;

        /**
         * Validate directive argument constraints, only if there are no previous errors
         */
        if (!$engineIterationFeedbackStore->hasErrors()) {
            if (
                $maybeErrorFeedbackItemResolutions = $this->resolveDirectiveArgumentErrors(
                    $relationalTypeResolver,
                )
            ) {
                foreach ($maybeErrorFeedbackItemResolutions as $errorFeedbackItemResolution) {
                    $engineIterationFeedbackStore->objectFeedbackStore->addError(
                        new ObjectResolutionFeedback(
                            $errorFeedbackItemResolution,
                            $this->directive,
                            $relationalTypeResolver,
                            $this->directive,
                            [$objectID => new EngineIterationFieldSet($fields)]
                        )
                    );
                }
            }
        }

        return [
            $validDirective,
            $directiveName,
            $directiveArgs,
        ];
    }

    /**
     * Indicate to what fieldNames this directive can be applied.
     * Returning an empty array means all of them
     */
    public function getFieldNamesToApplyTo(): array
    {
        // By default, apply to all fieldNames
        return [];
    }

    /**
     * ModuleConfiguration values cannot be accessed in `isServiceEnabled`,
     * because the DirectiveResolver services are initialized on
     * the "boot" event, and by then the `SchemaConfigurationExecuter`
     * services, to set-up configuration hooks, have not been initialized yet.
     * Then, the GraphQL custom endpoint will not have its Schema Configuration
     * applied.
     *
     * That's why it is done in this method instead.
     *
     * @see BootAttachExtensionCompilerPass.php
     */
    public function isDirectiveEnabled(): bool
    {
        return true;
    }

    /**
     * Define if to use the version to decide if to process the directive or not
     */
    public function decideCanProcessBasedOnVersionConstraint(RelationalTypeResolverInterface $relationalTypeResolver): bool
    {
        return false;
    }

    /**
     * By default, the directiveResolver instance can process the directive
     * This function can be overriden to force certain value on the directive args before it can be executed
     */
    public function resolveCanProcess(
        RelationalTypeResolverInterface $relationalTypeResolver,
        Directive $directive,
    ): bool {
        /** Check if to validate the version */
        if (
            Environment::enableSemanticVersionConstraints()
            && $this->decideCanProcessBasedOnVersionConstraint($relationalTypeResolver)
            && $this->hasDirectiveVersion($relationalTypeResolver)
        ) {
            /**
             * Please notice: we can get the fieldVersion directly from this instance,
             * and not from the schemaDefinition, because the version is set at the ObjectTypeFieldResolver level,
             * and not the InterfaceTypeFieldResolver, which is the other entity filling data
             * inside the schemaDefinition object.
             * If this directive is tagged with a version...
             */
            $schemaDirectiveVersion = $this->getDirectiveVersion($relationalTypeResolver);
            /**
             * Get versionConstraint in this order:
             * 1. Passed as directive argument
             * 2. Through param `directiveVersionConstraints[$directiveName]`: specific to the directive
             * 3. Through param `versionConstraint`: applies to all fields and directives in the query
             */
            $versionConstraint =
                $directive->getArgument(SchemaDefinition::VERSION_CONSTRAINT)?->getValue()
                ?? $this->getVersioningService()->getVersionConstraintsForDirective($this->getDirectiveName())
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

    /**
     * @return FeedbackItemResolution[] Errors
     */
    public function resolveDirectiveValidationErrors(
        RelationalTypeResolverInterface $relationalTypeResolver,
        Directive $directive,
    ): array {
        /**
         * Validate all mandatory args have been provided
         */
        $consolidatedDirectiveArgNameTypeResolvers = $this->getConsolidatedDirectiveArgNameTypeResolvers($relationalTypeResolver);
        $mandatoryConsolidatedDirectiveArgNames = array_keys(array_filter(
            $consolidatedDirectiveArgNameTypeResolvers,
            fn (string $directiveArgName) => ($this->getConsolidatedDirectiveArgTypeModifiers($relationalTypeResolver, $directiveArgName) & SchemaTypeModifiers::MANDATORY) === SchemaTypeModifiers::MANDATORY,
            ARRAY_FILTER_USE_KEY
        ));
        try {
            /**
             * @todo Replace with validateNotMissingFieldOrDirectiveArguments
             * @todo Remove the try/catch then
             */
            if (
                $maybeErrorFeedbackItemResolution = $this->deprecatedValidateNotMissingFieldOrDirectiveArguments(
                    $mandatoryConsolidatedDirectiveArgNames,
                    $directive,
                    ResolverTypes::DIRECTIVE
                )
            ) {
                return [$maybeErrorFeedbackItemResolution];
            }
        } catch (InvalidDynamicContextException $e) {
            $feedbackItemResolution = new FeedbackItemResolution(
                GenericFeedbackItemProvider::class,
                GenericFeedbackItemProvider::E1,
                [
                    $e->getMessage(),
                ]
            );
            return [$feedbackItemResolution];
        }

        /**
         * Validate directive argument constraints
         */
        if (
            $maybeErrorFeedbackItemResolutions = $this->resolveDirectiveArgumentErrors(
                $relationalTypeResolver,
            )
        ) {
            return $maybeErrorFeedbackItemResolutions;
        }

        // Custom validations
        return $this->doResolveSchemaValidationErrors(
            $relationalTypeResolver,
        );
    }

    /**
     * Validate the constraints for the directive arguments
     *
     * @return FeedbackItemResolution[] Errors
     */
    final protected function resolveDirectiveArgumentErrors(
        RelationalTypeResolverInterface $relationalTypeResolver,
    ): array {
        $errors = [];
        foreach ($this->directive->getArguments() as $argument) {
            try {
                if (
                    $maybeErrorFeedbackItemResolutions = $this->validateDirectiveArgValue(
                        $relationalTypeResolver,
                        $this->directive->getName(),
                        $argument->getName(),
                        $argument->getValue()
                    )
                ) {
                    $errors = array_merge(
                        $errors,
                        $maybeErrorFeedbackItemResolutions
                    );
                }
            } catch (InvalidDynamicContextException $e) {
                $errors[] = new FeedbackItemResolution(
                    GenericFeedbackItemProvider::class,
                    GenericFeedbackItemProvider::E1,
                    [
                        $e->getMessage(),
                    ]
                );
            }
        }
        return $errors;
    }

    /**
     * Validate the constraints for a directive argument
     *
     * @return FeedbackItemResolution[] Errors
     */
    protected function validateDirectiveArgValue(
        RelationalTypeResolverInterface $relationalTypeResolver,
        string $directiveName,
        string $directiveArgName,
        mixed $directiveArgValue
    ): array {
        return [];
    }

    /**
     * Custom validations. Function to override
     *
     * @return FeedbackItemResolution[] Errors
     */
    protected function doResolveSchemaValidationErrors(
        RelationalTypeResolverInterface $relationalTypeResolver,
    ): array {
        return [];
    }

    /**
     * @return mixed[]
     */
    protected function getExpressionsForObject(int | string $id, array $variables, array $messages): array
    {
        // Create a custom $variables containing all the properties from $resolvedIDFieldValues for this object
        // This way, when encountering $propName in a fieldArg in a fieldResolver, it can resolve that value
        // Otherwise it can't, since the fieldResolver doesn't have access to either $resolvedIDFieldValues
        return array_merge(
            $variables,
            $messages[self::MESSAGE_EXPRESSIONS_FOR_OBJECT][$id] ?? []
        );
    }

    /**
     * This function is needed to use in combination with @forEach
     * and inner nested directives. @forEach works by
     * setting all entries in the array under distinct fields
     * (["userPostData.0", "userPostData.1", "userPostData.2", etc]).
     *
     * Then, directives can target the value of an expression
     * to their nested directives, without fear of that value being overriden.
     *
     * Eg: in this query, the 1st @applyFunction is exporting the value
     * of `userLang` as an expression, to be read by the 2nd @applyFunction.
     * They can communicate passing data across,
     * because with `getExpressionsForObjectAndField`
     * they operate on the same expressions set by both ID and field:
     *
     * ```graphql
     *   userPostData: getSelfProp(self: "%{self}%", property: "userData")
     *     @forEach(affectDirectivesUnderPos: [1, 2])
     *       @applyFunction(
     *         name: "extract",
     *         arguments: {
     *           object: "%{value}%",
     *           path: "lang",
     *         },
     *         target: "userLang",
     *         targetType: EXPRESSION
     *       )
     *       @applyFunction(
     *         name: "sprintf",
     *         arguments: {
     *           string: "postContent-%s",
     *           values: ["%{userLang}%"]
     *         },
     *         target: "userPostContentKey",
     *         targetType: EXPRESSION
     *       )
     * ```
     *
     * If using `getExpressionsForObject` instead, each element in the array
     * traversed by @forEach would override the value of `userLang`, and so
     * only the value of the last item in the array will be made available as
     * `userLang` to ALL entries in the next @applyFunction.
     *
     * @return mixed[]
     */
    protected function getExpressionsForObjectAndField(int | string $id, FieldInterface $field, array $variables, array $messages): array
    {
        return array_merge(
            $this->getExpressionsForObject($id, $variables, $messages),
            $messages[self::MESSAGE_EXPRESSIONS_FOR_OBJECT_AND_FIELD][$id][$field->getOutputKey()] ?? []
        );
    }

    protected function addExpressionForObject(int | string $id, string $key, mixed $value, array &$messages): void
    {
        $messages[self::MESSAGE_EXPRESSIONS_FOR_OBJECT][$id][$key] = $value;
    }

    protected function addExpressionForObjectAndField(int | string $id, FieldInterface $field, string $key, mixed $value, array &$messages): void
    {
        $this->addExpressionForObject($id, $key, $value, $messages);
        $messages[self::MESSAGE_EXPRESSIONS_FOR_OBJECT_AND_FIELD][$id][$field->getOutputKey()][$key] = $value;
    }

    protected function getExpressionForObject(int | string $id, string $key, array $messages): mixed
    {
        return $messages[self::MESSAGE_EXPRESSIONS_FOR_OBJECT][$id][$key] ?? null;
    }

    protected function getExpressionForObjectAndField(int | string $id, FieldInterface $field, string $key, array $messages): mixed
    {
        return $messages[self::MESSAGE_EXPRESSIONS_FOR_OBJECT_AND_FIELD][$id][$field->getOutputKey()][$key] ?? $this->getExpressionForObject($id, $key, $messages);
    }

    /**
     * By default, place the directive after the ResolveAndMerge directive,
     * so the property will be in $resolvedIDFieldValues by then
     */
    public function getPipelinePosition(): string
    {
        return PipelinePositions::AFTER_RESOLVE_BEFORE_SERIALIZE;
    }

    /**
     * By default, a directive can be executed only one time for "Schema" and "System"
     * type directives (eg: <translate(en,es),translate(es,en)>),
     * and many times for the other types, "Query", "Scripting" and "Indexing"
     */
    public function isRepeatable(): bool
    {
        return !($this->getDirectiveKind() == DirectiveKinds::SYSTEM || $this->getDirectiveKind() == DirectiveKinds::SCHEMA);
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
        return !empty($this->getDirectiveVersion($relationalTypeResolver))
            && $this->getDirectiveVersionInputTypeResolver($relationalTypeResolver) !== null;
    }

    public function getDirectiveVersionInputTypeResolver(RelationalTypeResolverInterface $relationalTypeResolver): ?InputTypeResolverInterface
    {
        return null;
    }

    public function enableOrderedSchemaDirectiveArgs(RelationalTypeResolverInterface $relationalTypeResolver): bool
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($relationalTypeResolver);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->enableOrderedSchemaDirectiveArgs($relationalTypeResolver);
        }
        return true;
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
     * Eg: { posts { excerpt content @translate(affectAdditionalFieldsUnderPos: [1]) } }
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
        if ($consolidatedDirectiveArgNameTypeResolvers !== []) {
            /**
             * Add the version constraint (if enabled)
             * Only add the argument if this field or directive has a version
             * If it doesn't, then there will only be one version of it,
             * and it can be kept empty for simplicity
             */
            if (
                Environment::enableSemanticVersionConstraints()
                && $this->hasDirectiveVersion($relationalTypeResolver)
            ) {
                $consolidatedDirectiveArgNameTypeResolvers[SchemaDefinition::VERSION_CONSTRAINT] = $this->getDirectiveVersionInputTypeResolver($relationalTypeResolver);
            }
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

    protected function getDirectiveArgExtensionsSchemaDefinition(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): array
    {
        // @todo Implement "admin" directive args, if needed
        return [
            SchemaDefinition::IS_ADMIN_ELEMENT => false,
        ];
    }

    /**
     * Consolidation of the schema directive arguments. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
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

    /**
     * @return FeedbackItemResolution[]
     */
    public function resolveDirectiveValidationDeprecations(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveName, array $directiveArgs): array
    {
        return [];
    }

    public function getDirectiveWarning(RelationalTypeResolverInterface $relationalTypeResolver): ?FeedbackItemResolution
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($relationalTypeResolver);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getDirectiveWarning($relationalTypeResolver);
        }
        return null;
    }

    public function getDirectiveDeprecationMessage(RelationalTypeResolverInterface $relationalTypeResolver): ?string
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($relationalTypeResolver);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getDirectiveDeprecationMessage($relationalTypeResolver);
        }
        return null;
    }

    public function resolveDirectiveWarning(RelationalTypeResolverInterface $relationalTypeResolver): ?FeedbackItemResolution
    {
        if (Environment::enableSemanticVersionConstraints()) {
            /**
             * If restricting the version, and this fieldResolver doesn't have any version, then show a warning
             */
            if ($versionConstraint = $this->directiveArgsForSchema[SchemaDefinition::VERSION_CONSTRAINT] ?? null) {
                /**
                 * If this fieldResolver doesn't have versioning, then it accepts everything
                 */
                if (!$this->decideCanProcessBasedOnVersionConstraint($relationalTypeResolver)) {
                    return new FeedbackItemResolution(
                        WarningFeedbackItemProvider::class,
                        WarningFeedbackItemProvider::W3,
                        [
                            $this->getDirectiveName(),
                            $this->getDirectiveVersion($relationalTypeResolver) ?? '',
                            $versionConstraint,
                        ]
                    );
                }
            }
        }
        return $this->getDirectiveWarning($relationalTypeResolver);
    }

    public function getDirectiveExpressions(RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($relationalTypeResolver);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getDirectiveExpressions($relationalTypeResolver);
        }
        return [];
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
            $pipelineDirectiveResolvers,
            $idObjects,
            $unionTypeOutputKeyIDs,
            $previouslyResolvedIDFieldValues,
            $pipelineIDFieldSet,
            $pipelineFieldDataAccessProviders,
            $resolvedIDFieldValues,
            $variables,
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

        // The $pipelineDirectiveResolvers is the series of directives executed in the pipeline
        // The current stage is at the head. Remove it
        array_shift($pipelineDirectiveResolvers);

        // // 2. Validate operation
        // $this->validateDirective(
        //     $relationalTypeResolver,
        //     $idFieldSet,
        //     $pipelineIDFieldSet,
        //     $pipelineFieldDataAccessProviders,
        //     $pipelineDirectiveResolvers,
        //     $idObjects,
        //     $resolvedIDFieldValues,
        //     $previouslyResolvedIDFieldValues,
        //     $variables,
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
            try {
                $this->resolveDirective(
                    $relationalTypeResolver,
                    $idFieldSet,
                    $directiveDataAccessProvider,
                    $pipelineDirectiveResolvers,
                    $idObjects,
                    $unionTypeOutputKeyIDs,
                    $previouslyResolvedIDFieldValues,
                    $pipelineIDFieldSet,
                    $pipelineFieldDataAccessProviders,
                    $resolvedIDFieldValues,
                    $variables,
                    $messages,
                    $engineIterationFeedbackStore,
                );
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
                    $engineIterationFeedbackStore->objectFeedbackStore->addLog(
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
                $this->processFailure(
                    $relationalTypeResolver,
                    $feedbackItemResolution,
                    [],
                    $idFieldSet,
                    $pipelineIDFieldSet,
                    $idObjects,
                    $resolvedIDFieldValues,
                    $engineIterationFeedbackStore,
                );
            }
        }

        // 3. Re-create the payload from the modified variables
        return DirectivePipelineUtils::convertArgumentsToPayload(
            $relationalTypeResolver,
            $pipelineDirectiveResolvers,
            $idObjects,
            $unionTypeOutputKeyIDs,
            $previouslyResolvedIDFieldValues,
            $pipelineIDFieldSet,
            $pipelineFieldDataAccessProviders,
            $resolvedIDFieldValues,
            $variables,
            $messages,
            $engineIterationFeedbackStore,
        );
    }

    /**
     * Depending on environment configuration, either show a warning,
     * or show an error and remove the fields from the directive pipeline for further execution
     *
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     * @param array<array<string|int,EngineIterationFieldSet>> $succeedingPipelineIDFieldSet
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $resolvedIDFieldValues
     */
    protected function processFailure(
        RelationalTypeResolverInterface $relationalTypeResolver,
        FeedbackItemResolution $feedbackItemResolution,
        array $failedFields,
        array $idFieldSet,
        array &$succeedingPipelineIDFieldSet,
        array $idObjects,
        array &$resolvedIDFieldValues,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): void {
        $allFieldsFailed = empty($failedFields);
        if ($allFieldsFailed) {
            // Remove all fields
            $idFieldSetToRemove = $idFieldSet;
            // Calculate which fields are being removed, to add to the error
            foreach ($idFieldSet as $id => $fieldSet) {
                $failedFields = array_merge(
                    $failedFields,
                    $fieldSet->fields
                );
            }
            $failedFields = array_values(array_unique($failedFields));
        } else {
            $idFieldSetToRemove = [];
            // Calculate which fields to remove
            foreach ($idFieldSet as $id => $fieldSet) {
                $idFieldSetToRemove[$id] = new EngineIterationFieldSet(
                    array_intersect(
                        $fieldSet->fields,
                        $failedFields
                    )
                );
            }
        }
        // If the failure must be processed as an error, we must also remove the fields from the directive pipeline
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $removeFieldIfDirectiveFailed = $moduleConfiguration->removeFieldIfDirectiveFailed();
        if ($removeFieldIfDirectiveFailed) {
            $this->removeIDFieldSet(
                $idFieldSetToRemove,
                $succeedingPipelineIDFieldSet
            );
        }
        $setFailingFieldResponseAsNull = $moduleConfiguration->setFailingFieldResponseAsNull();
        if ($setFailingFieldResponseAsNull) {
            $this->setIDFieldSetAsNull(
                $relationalTypeResolver,
                $idFieldSetToRemove,
                $idObjects,
                $resolvedIDFieldValues,
            );
        }

        // Show the failureMessage either as error or as warning
        if ($setFailingFieldResponseAsNull) {
            $engineIterationFeedbackStore->objectFeedbackStore->addError(
                new ObjectResolutionFeedback(
                    $feedbackItemResolution,
                    $this->directive,
                    $relationalTypeResolver,
                    $this->directive,
                    $idFieldSetToRemove
                )
            );
        } elseif ($removeFieldIfDirectiveFailed) {
            $engineIterationFeedbackStore->objectFeedbackStore->addError(
                new ObjectResolutionFeedback(
                    $feedbackItemResolution,
                    $this->directive,
                    $relationalTypeResolver,
                    $this->directive,
                    $idFieldSetToRemove
                )
            );
        }
    }

    /**
     * Return the object implementing the schema definition for this DirectiveResolver.
     * By default, it is this same object
     */
    protected function getSchemaDefinitionResolver(RelationalTypeResolverInterface $relationalTypeResolver): SchemaDirectiveResolverInterface
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

    final public function getDirectiveSchemaDefinition(RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        // First check if the value was cached
        $key = $relationalTypeResolver->getNamespacedTypeName();
        if (!isset($this->schemaDefinitionForDirectiveCache[$key])) {
            $this->schemaDefinitionForDirectiveCache[$key] = $this->doGetDirectiveSchemaDefinition($relationalTypeResolver);
        }
        return $this->schemaDefinitionForDirectiveCache[$key];
    }

    private function doGetDirectiveSchemaDefinition(RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        $directiveName = $this->getDirectiveName();
        $schemaDefinition = [
            SchemaDefinition::NAME => $directiveName,
            SchemaDefinition::DIRECTIVE_KIND => $this->getDirectiveKind(),
            SchemaDefinition::DIRECTIVE_IS_REPEATABLE => $this->isRepeatable(),
            SchemaDefinition::DIRECTIVE_IS_GLOBAL => $this->isGlobal($relationalTypeResolver),
        ];
        if ($limitedToFields = $this->getFieldNamesToApplyTo()) {
            $schemaDefinition[SchemaDefinition::DIRECTIVE_LIMITED_TO_FIELDS] = $limitedToFields;
        }
        if ($description = $this->getDirectiveDescription($relationalTypeResolver)) {
            $schemaDefinition[SchemaDefinition::DESCRIPTION] = $description;
        }
        if ($expressions = $this->getDirectiveExpressions($relationalTypeResolver)) {
            $schemaDefinition[SchemaDefinition::DIRECTIVE_EXPRESSIONS] = $expressions;
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
        if (Environment::enableSemanticVersionConstraints() && $this->hasDirectiveVersion($relationalTypeResolver)) {
            $schemaDefinition[SchemaDefinition::VERSION] = $this->getDirectiveVersion($relationalTypeResolver);
        }
        $schemaDefinition[SchemaDefinition::EXTENSIONS] = $this->getDirectiveExtensionsSchemaDefinition($relationalTypeResolver);
        return $schemaDefinition;
    }

    public function getDirectiveExtensionsSchemaDefinition(RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        return [
            // @todo Implement "admin" directive, if needed
            SchemaDefinition::IS_ADMIN_ELEMENT => false,
            SchemaDefinition::DIRECTIVE_PIPELINE_POSITION => $this->getPipelinePosition(),
            SchemaDefinition::DIRECTIVE_NEEDS_DATA_TO_EXECUTE => $this->needsSomeIDFieldToExecute(),
        ];
    }
}
