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
use PoP\ComponentModel\FeedbackItemProviders\ErrorFeedbackItemProvider;
use PoP\ComponentModel\FeedbackItemProviders\WarningFeedbackItemProvider;
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
use PoP\ComponentModel\Resolvers\CheckDangerouslyNonSpecificScalarTypeFieldOrDirectiveResolverTrait;
use PoP\ComponentModel\Resolvers\FieldOrDirectiveSchemaDefinitionResolverTrait;
use PoP\ComponentModel\Resolvers\ObjectTypeOrDirectiveResolverTrait;
use PoP\ComponentModel\Resolvers\WithVersionConstraintFieldOrDirectiveResolverTrait;
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
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
use PoP\ComponentModel\Versioning\VersioningServiceInterface;
use PoP\GraphQLParser\ASTNodes\ASTNodesFactory;
use PoP\GraphQLParser\Exception\AbstractQueryException;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLSpecErrorFeedbackItemProvider;
use PoP\GraphQLParser\Module as GraphQLParserModule;
use PoP\GraphQLParser\ModuleConfiguration as GraphQLParserModuleConfiguration;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\App;
use PoP\Root\Exception\AbstractClientException;
use PoP\Root\FeedbackItemProviders\GenericFeedbackItemProvider;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\Root\Services\BasicServiceTrait;
use SplObjectStorage;

abstract class AbstractDirectiveResolver implements DirectiveResolverInterface
{
    use AttachableExtensionTrait;
    use RemoveIDFieldSetDirectiveResolverTrait;
    use FieldOrDirectiveSchemaDefinitionResolverTrait;
    use WithVersionConstraintFieldOrDirectiveResolverTrait;
    use BasicServiceTrait;
    use CheckDangerouslyNonSpecificScalarTypeFieldOrDirectiveResolverTrait;
    use ObjectTypeOrDirectiveResolverTrait;

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
     * To be set only if there were no validation errors.
     */
    protected DirectiveDataAccessorInterface $directiveDataAccessor;
    protected bool $hasValidationErrors;

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

    final public function setSemverHelperService(SemverHelperServiceInterface $semverHelperService): void
    {
        $this->semverHelperService = $semverHelperService;
    }
    final protected function getSemverHelperService(): SemverHelperServiceInterface
    {
        /** @var SemverHelperServiceInterface */
        return $this->semverHelperService ??= $this->instanceManager->getInstance(SemverHelperServiceInterface::class);
    }
    final public function setAttachableExtensionManager(AttachableExtensionManagerInterface $attachableExtensionManager): void
    {
        $this->attachableExtensionManager = $attachableExtensionManager;
    }
    final protected function getAttachableExtensionManager(): AttachableExtensionManagerInterface
    {
        /** @var AttachableExtensionManagerInterface */
        return $this->attachableExtensionManager ??= $this->instanceManager->getInstance(AttachableExtensionManagerInterface::class);
    }
    final public function setDangerouslyNonSpecificScalarTypeScalarTypeResolver(DangerouslyNonSpecificScalarTypeScalarTypeResolver $dangerouslyNonSpecificScalarTypeScalarTypeResolver): void
    {
        $this->dangerouslyNonSpecificScalarTypeScalarTypeResolver = $dangerouslyNonSpecificScalarTypeScalarTypeResolver;
    }
    final protected function getDangerouslyNonSpecificScalarTypeScalarTypeResolver(): DangerouslyNonSpecificScalarTypeScalarTypeResolver
    {
        /** @var DangerouslyNonSpecificScalarTypeScalarTypeResolver */
        return $this->dangerouslyNonSpecificScalarTypeScalarTypeResolver ??= $this->instanceManager->getInstance(DangerouslyNonSpecificScalarTypeScalarTypeResolver::class);
    }
    final public function setVersioningService(VersioningServiceInterface $versioningService): void
    {
        $this->versioningService = $versioningService;
    }
    final protected function getVersioningService(): VersioningServiceInterface
    {
        /** @var VersioningServiceInterface */
        return $this->versioningService ??= $this->instanceManager->getInstance(VersioningServiceInterface::class);
    }
    final public function setIntScalarTypeResolver(IntScalarTypeResolver $intScalarTypeResolver): void
    {
        $this->intScalarTypeResolver = $intScalarTypeResolver;
    }
    final protected function getIntScalarTypeResolver(): IntScalarTypeResolver
    {
        /** @var IntScalarTypeResolver */
        return $this->intScalarTypeResolver ??= $this->instanceManager->getInstance(IntScalarTypeResolver::class);
    }
    final public function setSchemaCastingService(SchemaCastingServiceInterface $schemaCastingService): void
    {
        $this->schemaCastingService = $schemaCastingService;
    }
    final protected function getSchemaCastingService(): SchemaCastingServiceInterface
    {
        /** @var SchemaCastingServiceInterface */
        return $this->schemaCastingService ??= $this->instanceManager->getInstance(SchemaCastingServiceInterface::class);
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
            ASTNodesFactory::getNonSpecificLocation()
        );
    }

    /**
     * Invoked when creating the non-shared directive instance
     * to resolve a directive in the pipeline
     */
    final public function setDirective(Directive $directive): void
    {
        $this->directive = $directive;
    }

    public function getDirective(): Directive
    {
        return $this->directive;
    }

    /**
     * @return string[]
     */
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
    public function resolveCanProcessDirective(
        RelationalTypeResolverInterface $relationalTypeResolver,
        Directive $directive,
    ): bool {
        /** Check if to validate the version */
        if (
            Environment::enableSemanticVersionConstraints()
            && $this->decideCanProcessBasedOnVersionConstraint($relationalTypeResolver)
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

    final public function resolveCanProcessField(
        RelationalTypeResolverInterface $relationalTypeResolver,
        FieldInterface $field,
        bool $isNested,
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
            return $this->resolveCanProcessField($targetObjectTypeResolver, $field, $isNested);
        }

        /**
         * Nested directives must not validate the type,
         * as they will be most likely applied on a subitem
         * from the field value (eg: an array item, or a JSON
         * property).
         *
         * Eg: in this query, the field is of type JSONObject,
         * but the directive is applied on a string
         *
         * ```
         * {
         *   postData: getJSON(
         *     url: "https://newapi.getpop.org/wp-json/wp/v2/posts/1/?_fields=id,type,title,date"
         *   )
         *     @underJSONObjectProperty(path: "title.rendered")
         *       @upperCase
         * }
         * ```
         */
        if ($isNested) {
            return true;
        }

        /** @var ObjectTypeResolverInterface */
        $objectTypeResolver = $relationalTypeResolver;
        $fieldTypeResolver = $objectTypeResolver->getFieldTypeResolver($field);

        /**
         * There will be a GraphQL error somewhere else,
         * process the field as to avoid adding yet another error
         */
        if ($fieldTypeResolver === null) {
            return true;
        }

        /**
         * DangerousNonSpecific is always processed
         */
        if ($fieldTypeResolver instanceof DangerouslyNonSpecificScalarTypeScalarTypeResolver) {
            return true;
        }

        /**
         * Check if the directive only handles specific types
         */
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

        return true;
    }

    /**
     * @return array<class-string<ConcreteTypeResolverInterface>>|null
     */
    protected function getSupportedFieldTypeResolverClasses(): ?array
    {
        return null;
    }

    /**
     * Validate the constraints for a directive argument
     */
    protected function validateDirectiveArgValue(
        string $directiveArgName,
        mixed $directiveArgValue,
        AstInterface $astNode,
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

    /**
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     */
    protected function maybeAddSemanticVersionConstraintsWarningFeedback(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $idFieldSet,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): void {
        if (!Environment::enableSemanticVersionConstraints()) {
            return;
        }

        /**
         * If restricting the version, and this fieldResolver doesn't have any version, then show a warning
         */
        $directiveArgs = $this->directiveDataAccessor->getDirectiveArgs();
        $versionConstraint = $directiveArgs[SchemaDefinition::VERSION_CONSTRAINT] ?? null;
        if (!$versionConstraint) {
            return;
        }

        /**
         * If this fieldResolver doesn't have versioning, then it accepts everything
         */
        if ($this->decideCanProcessBasedOnVersionConstraint($relationalTypeResolver)) {
            return;
        }

        $fields = MethodHelpers::getFieldsFromIDFieldSet($idFieldSet);
        $engineIterationFeedbackStore->schemaFeedbackStore->addWarning(
            new SchemaFeedback(
                new FeedbackItemResolution(
                    WarningFeedbackItemProvider::class,
                    WarningFeedbackItemProvider::W3,
                    [
                        $this->getDirectiveName(),
                        $this->getDirectiveVersion($relationalTypeResolver) ?? '',
                        $versionConstraint,
                    ]
                ),
                $this->directive,
                $relationalTypeResolver,
                $fields
            )
        );
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
        //     $messages,
        // );

        // 2. Execute operation.
        // First check that if the validation took away the elements, and so the directive can't execute anymore
        // For instance, executing ?query=posts.id|title<default,translate(from:en,to:es)> will fail
        // after directive "default", so directive "translate" must not even execute
        if (!$this->needsSomeIDFieldToExecute() || $this->hasSomeIDField($idFieldSet)) {
            $this->maybeAddSemanticVersionConstraintsWarningFeedback(
                $relationalTypeResolver,
                $idFieldSet,
                $engineIterationFeedbackStore,
            );

            // If the directive resolver throws an Exception,
            // catch it and add objectErrors
            $feedbackItemResolution = null;
            $astNode = null;
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
                    $messages,
                    $engineIterationFeedbackStore,
                );
            } catch (AbstractQueryException $queryException) {
                $feedbackItemResolution = $queryException->getFeedbackItemResolution();
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
            $pipelineDirectiveResolvers,
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
            $this->setFailingFieldResponseAsNull(
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
    protected function setFailingFieldResponseAsNull(
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
        if (Environment::enableSemanticVersionConstraints() && $this->hasDirectiveVersion($relationalTypeResolver)) {
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
        ];
    }
}
