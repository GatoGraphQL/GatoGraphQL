<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

use Exception;
use PoP\ComponentModel\AttachableExtensions\AttachableExtensionTrait;
use PoP\ComponentModel\ComponentConfiguration;
use PoP\ComponentModel\DirectivePipeline\DirectivePipelineUtils;
use PoP\ComponentModel\Directives\DirectiveTypes;
use PoP\ComponentModel\Environment;
use PoP\ComponentModel\Feedback\Tokens;
use PoP\ComponentModel\HelperServices\SemverHelperServiceInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Resolvers\FieldOrDirectiveResolverTrait;
use PoP\ComponentModel\Resolvers\ResolverTypes;
use PoP\ComponentModel\Resolvers\WithVersionConstraintFieldOrDirectiveResolverTrait;
use PoP\ComponentModel\Schema\FeedbackMessageStoreInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\TypeResolvers\EnumType\EnumTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\FieldSymbols;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\PipelinePositions;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\Versioning\VersioningHelpers;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\FieldQuery\QueryHelpers;
use PoP\Hooks\HooksAPIInterface;
use PoP\Root\Environment as RootEnvironment;
use PoP\Translation\TranslationAPIInterface;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractDirectiveResolver implements DirectiveResolverInterface
{
    use AttachableExtensionTrait;
    use RemoveIDsDataFieldsDirectiveResolverTrait;
    use FieldOrDirectiveResolverTrait;
    use WithVersionConstraintFieldOrDirectiveResolverTrait;

    const MESSAGE_EXPRESSIONS = 'expressions';

    protected string $directive;
    /** @var array<string, array<string, InputTypeResolverInterface>> */
    protected array $consolidatedDirectiveArgNameTypeResolversCache = [];
    /** @var array<string, string|null> */
    protected array $consolidatedDirectiveArgDescriptionCache = [];
    /** @var array<string, string|null> */
    protected array $consolidatedDirectiveArgDeprecationMessageCache = [];
    /** @var array<string, mixed> */
    protected array $consolidatedDirectiveArgDefaultValueCache = [];
    /** @var array<string, int> */
    protected array $consolidatedDirectiveArgTypeModifiersCache = [];
    /** @var array<string, array<string, mixed>> */
    protected array $schemaDirectiveArgsCache = [];

    protected TranslationAPIInterface $translationAPI;
    protected HooksAPIInterface $hooksAPI;
    protected InstanceManagerInterface $instanceManager;
    protected FieldQueryInterpreterInterface $fieldQueryInterpreter;
    protected FeedbackMessageStoreInterface $feedbackMessageStore;
    protected SemverHelperServiceInterface $semverHelperService;
    protected StringScalarTypeResolver $stringScalarTypeResolver;

    /**
     * @var array<string, mixed>
     */
    protected array $directiveArgsForSchema = [];
    /**
     * @var array<string, mixed>
     */
    protected array $directiveArgsForObjects = [];
    /**
     * @var array[]
     */
    protected array $nestedDirectivePipelineData = [];

    /**
     * @var array<string, array>
     */
    protected array $schemaDefinitionForDirectiveCache = [];

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
        $this->directive = $this->getDirectiveName();
    }

    /**
     * Invoked when creating the non-shared directive instance
     * to resolve a field in the pipeline
     */
    final public function setDirective(string $directive): void
    {
        $this->directive = $directive;
    }

    #[Required]
    final public function autowireAbstractDirectiveResolver(
        TranslationAPIInterface $translationAPI,
        HooksAPIInterface $hooksAPI,
        InstanceManagerInterface $instanceManager,
        FieldQueryInterpreterInterface $fieldQueryInterpreter,
        FeedbackMessageStoreInterface $feedbackMessageStore,
        SemverHelperServiceInterface $semverHelperService,
        StringScalarTypeResolver $stringScalarTypeResolver,
    ): void {
        $this->translationAPI = $translationAPI;
        $this->hooksAPI = $hooksAPI;
        $this->instanceManager = $instanceManager;
        $this->fieldQueryInterpreter = $fieldQueryInterpreter;
        $this->feedbackMessageStore = $feedbackMessageStore;
        $this->semverHelperService = $semverHelperService;
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
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
    public function getDirectiveType(): string
    {
        return DirectiveTypes::QUERY;
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

    public function dissectAndValidateDirectiveForSchema(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array &$fieldDirectiveFields,
        array &$variables,
        array &$schemaErrors,
        array &$schemaWarnings,
        array &$schemaDeprecations,
        array &$schemaNotices,
        array &$schemaTraces
    ): array {
        // If it has nestedDirectives, extract them and validate them
        $nestedFieldDirectives = $this->fieldQueryInterpreter->getFieldDirectives($this->directive, false);
        if ($nestedFieldDirectives) {
            $nestedDirectiveSchemaErrors = $nestedDirectiveSchemaWarnings = $nestedDirectiveSchemaDeprecations = $nestedDirectiveSchemaNotices = $nestedDirectiveSchemaTraces = [];
            $nestedFieldDirectives = QueryHelpers::splitFieldDirectives($nestedFieldDirectives);
            // Support repeated fields by adding a counter next to them
            if (count($nestedFieldDirectives) != count(array_unique($nestedFieldDirectives))) {
                // Find the repeated fields, and add a counter next to them
                $expandedNestedFieldDirectives = [];
                $counters = [];
                foreach ($nestedFieldDirectives as $nestedFieldDirective) {
                    if (!isset($counters[$nestedFieldDirective])) {
                        $expandedNestedFieldDirectives[] = $nestedFieldDirective;
                        $counters[$nestedFieldDirective] = 1;
                    } else {
                        $expandedNestedFieldDirectives[] = $nestedFieldDirective . FieldSymbols::REPEATED_DIRECTIVE_COUNTER_SEPARATOR . $counters[$nestedFieldDirective];
                        $counters[$nestedFieldDirective]++;
                    }
                }
                $nestedFieldDirectives = $expandedNestedFieldDirectives;
            }
            // Each composed directive will deal with the same fields as the current directive
            $nestedFieldDirectiveFields = $fieldDirectiveFields;
            foreach ($nestedFieldDirectives as $nestedFieldDirective) {
                $nestedFieldDirectiveFields[$nestedFieldDirective] = $fieldDirectiveFields[$this->directive];
            }
            $this->nestedDirectivePipelineData = $relationalTypeResolver->resolveDirectivesIntoPipelineData(
                $nestedFieldDirectives,
                $nestedFieldDirectiveFields,
                true,
                $variables,
                $nestedDirectiveSchemaErrors,
                $nestedDirectiveSchemaWarnings,
                $nestedDirectiveSchemaDeprecations,
                $nestedDirectiveSchemaNotices,
                $nestedDirectiveSchemaTraces
            );
            foreach ($nestedDirectiveSchemaDeprecations as $nestedDirectiveSchemaDeprecation) {
                array_unshift($nestedDirectiveSchemaDeprecation[Tokens::PATH], $this->directive);
                $schemaDeprecations[] = $nestedDirectiveSchemaDeprecation;
            }
            foreach ($nestedDirectiveSchemaWarnings as $nestedDirectiveSchemaWarning) {
                array_unshift($nestedDirectiveSchemaWarning[Tokens::PATH], $this->directive);
                $schemaWarnings[] = $nestedDirectiveSchemaWarning;
            }
            foreach ($nestedDirectiveSchemaNotices as $nestedDirectiveSchemaNotice) {
                array_unshift($nestedDirectiveSchemaNotice[Tokens::PATH], $this->directive);
                $schemaNotices[] = $nestedDirectiveSchemaNotice;
            }
            foreach ($nestedDirectiveSchemaTraces as $nestedDirectiveSchemaTrace) {
                array_unshift($nestedDirectiveSchemaTrace[Tokens::PATH], $this->directive);
                $schemaTraces[] = $nestedDirectiveSchemaTrace;
            }
            // If there is any error, then we also can't proceed with the current directive.
            // Throw an error for this level, and underlying errors as nested
            if ($nestedDirectiveSchemaErrors) {
                $schemaError = [
                    Tokens::PATH => [$this->directive],
                    Tokens::MESSAGE => $this->translationAPI->__('This directive can\'t be executed due to errors from its composed directives', 'component-model'),
                ];
                foreach ($nestedDirectiveSchemaErrors as $nestedDirectiveSchemaError) {
                    array_unshift($nestedDirectiveSchemaError[Tokens::PATH], $this->directive);
                    $this->prependPathOnNestedErrors($nestedDirectiveSchemaError);
                    $schemaError[Tokens::EXTENSIONS][Tokens::NESTED][] = $nestedDirectiveSchemaError;
                }
                $schemaErrors[] = $schemaError;
                return [
                    null, // $validDirective
                    null, // $directiveName
                    null, // $directiveArgs
                ];
            }
        }

        // First validate schema (eg of error in schema: ?query=posts<include(if:this-field-doesnt-exist())>)
        list(
            $validDirective,
            $directiveName,
            $directiveArgs,
            $directiveSchemaErrors,
            $directiveSchemaWarnings,
            $directiveSchemaDeprecations
        ) = $this->fieldQueryInterpreter->extractDirectiveArgumentsForSchema(
            $this,
            $relationalTypeResolver,
            $this->directive,
            $variables,
            $this->disableDynamicFieldsFromDirectiveArgs()
        );

        // Store the args, they may be used in `resolveDirective`
        $this->directiveArgsForSchema = $directiveArgs;

        // If there were errors, warning or deprecations, integrate them into the feedback objects
        $schemaErrors = array_merge(
            $schemaErrors,
            $directiveSchemaErrors
        );
        $schemaWarnings = array_merge(
            $schemaWarnings,
            $directiveSchemaWarnings
        );
        $schemaDeprecations = array_merge(
            $schemaDeprecations,
            $directiveSchemaDeprecations
        );
        return [
            $validDirective,
            $directiveName,
            $directiveArgs,
        ];
    }

    /**
     * Add the directive to the head of the error path, for all nested errors
     */
    protected function prependPathOnNestedErrors(array &$nestedDirectiveSchemaError): void
    {

        if (isset($nestedDirectiveSchemaError[Tokens::EXTENSIONS][Tokens::NESTED])) {
            foreach ($nestedDirectiveSchemaError[Tokens::EXTENSIONS][Tokens::NESTED] as &$deeplyNestedDirectiveSchemaError) {
                array_unshift($deeplyNestedDirectiveSchemaError[Tokens::PATH], $this->directive);
                $this->prependPathOnNestedErrors($deeplyNestedDirectiveSchemaError);
            }
        }
    }

    /**
     * By default, validate if there are deprecated fields
     */
    public function validateDirectiveArgumentsForSchema(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveName, array $directiveArgs, array &$schemaErrors, array &$schemaWarnings, array &$schemaDeprecations): array
    {
        $deprecationMessages = $this->resolveDirectiveDeprecationMessages(
            $relationalTypeResolver,
            $directiveName,
            $directiveArgs
        );
        foreach ($deprecationMessages as $deprecationMessage) {
            $schemaDeprecations[] = [
                Tokens::PATH => [$this->directive],
                Tokens::MESSAGE => $deprecationMessage,
            ];
        }
        return $directiveArgs;
    }

    public function dissectAndValidateDirectiveForObject(
        RelationalTypeResolverInterface $relationalTypeResolver,
        object $object,
        array &$variables,
        array &$expressions,
        array &$objectErrors,
        array &$objectWarnings,
        array &$objectDeprecations
    ): array {
        list(
            $validDirective,
            $directiveName,
            $directiveArgs,
            $nestedObjectErrors,
            $nestedObjectWarnings
        ) = $this->fieldQueryInterpreter->extractDirectiveArgumentsForObject($this, $relationalTypeResolver, $object, $this->directive, $variables, $expressions);

        // Store the args, they may be used in `resolveDirective`
        $objectID = $relationalTypeResolver->getID($object);
        $this->directiveArgsForObjects[$objectID] = $directiveArgs;

        // Store errors (if any)
        foreach ($nestedObjectErrors as $id => $fieldOutputKeyErrorMessages) {
            $objectErrors[$id] = array_merge(
                $objectErrors[$id] ?? [],
                $fieldOutputKeyErrorMessages
            );
        }
        foreach ($nestedObjectWarnings as $id => $fieldOutputKeyWarningMessages) {
            $objectWarnings[$id] = array_merge(
                $objectWarnings[$id] ?? [],
                $fieldOutputKeyWarningMessages
            );
        }

        /**
         * Validate directive argument constraints, only if there are no previous errors
         */
        if (!$nestedObjectErrors) {
            if (
                $maybeErrors = $this->resolveDirectiveArgumentErrors(
                    $relationalTypeResolver,
                    $directiveName,
                    $directiveArgs
                )
            ) {
                foreach ($maybeErrors as $errorMessage) {
                    $objectErrors[$objectID][] = [
                        Tokens::PATH => [$this->directive],
                        Tokens::MESSAGE => $errorMessage,
                    ];
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
    public function resolveCanProcess(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveName, array $directiveArgs, string $field, array &$variables): bool
    {
        /** Check if to validate the version */
        if (
            Environment::enableSemanticVersionConstraints() &&
            $this->decideCanProcessBasedOnVersionConstraint($relationalTypeResolver)
        ) {
            /**
             * Please notice: we can get the fieldVersion directly from this instance,
             * and not from the schemaDefinition, because the version is set at the ObjectTypeFieldResolver level,
             * and not the InterfaceTypeFieldResolver, which is the other entity filling data
             * inside the schemaDefinition object.
             * If this directive is tagged with a version...
             */
            if ($schemaDirectiveVersion = $this->getDirectiveVersion($relationalTypeResolver)) {
                $vars = ApplicationState::getVars();
                /**
                 * Get versionConstraint in this order:
                 * 1. Passed as directive argument
                 * 2. Through param `directiveVersionConstraints[$directiveName]`: specific to the directive
                 * 3. Through param `versionConstraint`: applies to all fields and directives in the query
                 */
                $versionConstraint =
                    $directiveArgs[SchemaDefinition::VERSION_CONSTRAINT]
                    ?? VersioningHelpers::getVersionConstraintsForDirective($this->getDirectiveName())
                    ?? $vars['version-constraint'];
                /**
                 * If the query doesn't restrict the version, then do not process
                 */
                if (!$versionConstraint) {
                    return false;
                }
                /**
                 * Compare using semantic versioning constraint rules, as used by Composer
                 * If passing a wrong value to validate against (eg: "saraza" instead of "1.0.0"), it will throw an Exception
                 */
                try {
                    return $this->semverHelperService->satisfies($schemaDirectiveVersion, $versionConstraint);
                } catch (Exception) {
                    return false;
                }
            }
        }
        return true;
    }

    public function resolveFieldValidationErrorDescriptions(
        RelationalTypeResolverInterface $relationalTypeResolver,
        string $directiveName,
        array $directiveArgs = []
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
        if (
            $maybeError = $this->validateNotMissingFieldOrDirectiveArguments(
                $mandatoryConsolidatedDirectiveArgNames,
                $directiveName,
                $directiveArgs,
                ResolverTypes::DIRECTIVE
            )
        ) {
            return [$maybeError];
        }

        if ($this->canValidateFieldOrDirectiveArgumentsWithValuesForSchema($directiveArgs)) {
            /**
             * Validate all enum values provided via args are valid
             */
            /** @var array<string, EnumTypeResolverInterface> */
            $enumConsolidatedDirectiveArgNameTypeResolvers = array_filter(
                $consolidatedDirectiveArgNameTypeResolvers,
                fn (InputTypeResolverInterface $inputTypeResolver) => $inputTypeResolver instanceof EnumTypeResolverInterface
            );
            $enumConsolidatedDirectiveArgNamesIsArrayOfArrays = $enumConsolidatedDirectiveArgNamesIsArray = [];
            foreach (array_keys($enumConsolidatedDirectiveArgNameTypeResolvers) as $directiveArgName) {
                $consolidatedDirectiveArgTypeModifiers = $this->getConsolidatedDirectiveArgTypeModifiers($relationalTypeResolver, $directiveArgName);
                $enumConsolidatedDirectiveArgNamesIsArrayOfArrays[$directiveArgName] = ($consolidatedDirectiveArgTypeModifiers & SchemaTypeModifiers::IS_ARRAY_OF_ARRAYS) === SchemaTypeModifiers::IS_ARRAY_OF_ARRAYS;
                $enumConsolidatedDirectiveArgNamesIsArray[$directiveArgName] = ($consolidatedDirectiveArgTypeModifiers & SchemaTypeModifiers::IS_ARRAY) === SchemaTypeModifiers::IS_ARRAY;
            }
            [$maybeErrors] = $this->validateEnumFieldOrDirectiveArguments(
                $enumConsolidatedDirectiveArgNameTypeResolvers,
                $enumConsolidatedDirectiveArgNamesIsArrayOfArrays,
                $enumConsolidatedDirectiveArgNamesIsArray,
                $directiveName,
                $directiveArgs,
                ResolverTypes::DIRECTIVE
            );
            if ($maybeErrors) {
                return $maybeErrors;
            }

            /**
             * Validate directive argument constraints
             */
            if (
                $maybeErrors = $this->resolveDirectiveArgumentErrors(
                    $relationalTypeResolver,
                    $directiveName,
                    $directiveArgs
                )
            ) {
                return $maybeErrors;
            }
        }

        // Custom validations
        return $this->doResolveSchemaValidationErrorDescriptions(
            $relationalTypeResolver,
            $directiveName,
            $directiveArgs,
        );
    }

    /**
     * Validate the constraints for the directive arguments
     */
    final protected function resolveDirectiveArgumentErrors(
        RelationalTypeResolverInterface $relationalTypeResolver,
        string $directiveName,
        array $directiveArgs = []
    ): array {
        $errors = [];
        foreach ($directiveArgs as $directiveArgName => $directiveArgValue) {
            if (
                $maybeErrors = $this->validateDirectiveArgument(
                    $relationalTypeResolver,
                    $directiveName,
                    $directiveArgName,
                    $directiveArgValue
                )
            ) {
                $errors = array_merge(
                    $errors,
                    $maybeErrors
                );
            }
        }
        return $errors;
    }

    /**
     * Validate the constraints for a directive argument
     */
    protected function validateDirectiveArgument(
        RelationalTypeResolverInterface $relationalTypeResolver,
        string $directiveName,
        string $directiveArgName,
        mixed $directiveArgValue
    ): array {
        return [];
    }

    /**
     * Custom validations. Function to override
     */
    protected function doResolveSchemaValidationErrorDescriptions(
        RelationalTypeResolverInterface $relationalTypeResolver,
        string $directiveName,
        array $directiveArgs = []
    ): array {
        return [];
    }

    /**
     * @return mixed[]
     */
    protected function getExpressionsForObject(int | string $id, array &$variables, array &$messages): array
    {
        // Create a custom $variables containing all the properties from $dbItems for this object
        // This way, when encountering $propName in a fieldArg in a fieldResolver, it can resolve that value
        // Otherwise it can't, since the fieldResolver doesn't have access to either $dbItems
        return array_merge(
            $variables,
            $messages[self::MESSAGE_EXPRESSIONS][(string)$id] ?? []
        );
    }

    protected function addExpressionForObject(int | string $id, string $key, mixed $value, array &$messages): void
    {
        $messages[self::MESSAGE_EXPRESSIONS][(string)$id][$key] = $value;
    }

    protected function getExpressionForObject(int | string $id, string $key, array &$messages): mixed
    {
        return $messages[self::MESSAGE_EXPRESSIONS][(string)$id][$key] ?? null;
    }

    /**
     * By default, place the directive after the ResolveAndMerge directive, so the property will be in $dbItems by then
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
        return !($this->getDirectiveType() == DirectiveTypes::SYSTEM || $this->getDirectiveType() == DirectiveTypes::SCHEMA);
    }

    /**
     * Indicate if the directive needs to be passed $idsDataFields filled with data to be able to execute
     * Because most commonly it will need, the default value is `true`
     */
    public function needsIDsDataFieldsToExecute(): bool
    {
        return true;
    }

    /**
     * Indicate that there is data in variable $idsDataFields
     */
    protected function hasIDsDataFields(array &$idsDataFields): bool
    {
        foreach ($idsDataFields as $id => &$data_fields) {
            if ($data_fields['direct'] ?? null) {
                // If there's data-fields to fetch for any ID, that's it, there's data
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

    public function enableOrderedSchemaDirectiveArgs(RelationalTypeResolverInterface $relationalTypeResolver): bool
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($relationalTypeResolver);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->enableOrderedSchemaDirectiveArgs($relationalTypeResolver);
        }
        return true;
    }

    /**
     * @return array<string, InputTypeResolverInterface>
     */
    public function getDirectiveArgNameTypeResolvers(RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($relationalTypeResolver);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getDirectiveArgNameTypeResolvers($relationalTypeResolver);
        }
        return [];
    }

    public function getDirectiveArgDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): ?string
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($relationalTypeResolver);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getDirectiveArgDescription($relationalTypeResolver, $directiveArgName);
        }
        // Version constraint (possibly enabled)
        if ($directiveArgName === SchemaDefinition::VERSION_CONSTRAINT) {
            return $this->getVersionConstraintFieldOrDirectiveArgDescription();
        }
        return null;
    }

    public function getDirectiveArgDeprecationMessage(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): ?string
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($relationalTypeResolver);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getDirectiveArgDeprecationMessage($relationalTypeResolver, $directiveArgName);
        }
        return null;
    }

    public function getDirectiveArgDefaultValue(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): mixed
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($relationalTypeResolver);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getDirectiveArgDefaultValue($relationalTypeResolver, $directiveArgName);
        }
        return null;
    }

    public function getDirectiveArgTypeModifiers(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): int
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($relationalTypeResolver);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getDirectiveArgTypeModifiers($relationalTypeResolver, $directiveArgName);
        }
        return SchemaTypeModifiers::NONE;
    }

    /**
     * Consolidation of the schema directive arguments. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     */
    final public function getConsolidatedDirectiveArgNameTypeResolvers(RelationalTypeResolverInterface $relationalTypeResolver): array
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
        $consolidatedDirectiveArgNameTypeResolvers = $this->hooksAPI->applyFilters(
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
            if (Environment::enableSemanticVersionConstraints()) {
                $hasVersion = !empty($this->getDirectiveVersion($relationalTypeResolver));
                if ($hasVersion) {
                    $consolidatedDirectiveArgNameTypeResolvers[SchemaDefinition::VERSION_CONSTRAINT] = $this->stringScalarTypeResolver;
                }
            }
        }
        $this->consolidatedDirectiveArgNameTypeResolversCache[$cacheKey] = $consolidatedDirectiveArgNameTypeResolvers;
        return $this->consolidatedDirectiveArgNameTypeResolversCache[$cacheKey];
    }

    /**
     * Consolidation of the schema directive arguments. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     */
    final public function getConsolidatedDirectiveArgDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): ?string
    {
        // Cache the result
        $cacheKey = $relationalTypeResolver::class . '(' . $directiveArgName . ':)';
        if (array_key_exists($cacheKey, $this->consolidatedDirectiveArgDescriptionCache)) {
            return $this->consolidatedDirectiveArgDescriptionCache[$cacheKey];
        }
        $this->consolidatedDirectiveArgDescriptionCache[$cacheKey] = $this->hooksAPI->applyFilters(
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
    final public function getConsolidatedDirectiveArgDefaultValue(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): mixed
    {
        // Cache the result
        $cacheKey = $relationalTypeResolver::class . '(' . $directiveArgName . ':)';
        if (array_key_exists($cacheKey, $this->consolidatedDirectiveArgDefaultValueCache)) {
            return $this->consolidatedDirectiveArgDefaultValueCache[$cacheKey];
        }
        $this->consolidatedDirectiveArgDefaultValueCache[$cacheKey] = $this->hooksAPI->applyFilters(
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
    final public function getConsolidatedDirectiveArgTypeModifiers(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): int
    {
        // Cache the result
        $cacheKey = $relationalTypeResolver::class . '(' . $directiveArgName . ':)';
        if (array_key_exists($cacheKey, $this->consolidatedDirectiveArgTypeModifiersCache)) {
            return $this->consolidatedDirectiveArgTypeModifiersCache[$cacheKey];
        }
        $this->consolidatedDirectiveArgTypeModifiersCache[$cacheKey] = $this->hooksAPI->applyFilters(
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
            $schemaDirectiveArgs[$directiveArgName] = $this->getFieldOrDirectiveArgTypeSchemaDefinition(
                $directiveArgName,
                $directiveArgInputTypeResolver,
                $this->getConsolidatedDirectiveArgDescription($relationalTypeResolver, $directiveArgName),
                $this->getConsolidatedDirectiveArgDefaultValue($relationalTypeResolver, $directiveArgName),
                $this->getConsolidatedDirectiveArgTypeModifiers($relationalTypeResolver, $directiveArgName),
            );
        }
        $this->schemaDirectiveArgsCache[$cacheKey] = $schemaDirectiveArgs;
        return $this->schemaDirectiveArgsCache[$cacheKey];
    }

    /**
     * @return string[]
     */
    public function resolveDirectiveDeprecationMessages(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveName, array $directiveArgs = []): array
    {
        $directiveDeprecationMessages = [];

        /**
         * Deprecations for the directive args of Enum Type
         */
        $consolidatedDirectiveArgNameTypeResolvers = $this->getConsolidatedDirectiveArgNameTypeResolvers($relationalTypeResolver);
        /** @var array<string, EnumTypeResolverInterface> */
        $enumConsolidatedDirectiveArgNameTypeResolvers = array_filter(
            $consolidatedDirectiveArgNameTypeResolvers,
            fn (InputTypeResolverInterface $inputTypeResolver) => $inputTypeResolver instanceof EnumTypeResolverInterface
        );
        $enumConsolidatedDirectiveArgNamesIsArrayOfArrays = $enumConsolidatedDirectiveArgNamesIsArray = [];
        foreach (array_keys($enumConsolidatedDirectiveArgNameTypeResolvers) as $directiveArgName) {
            $consolidatedDirectiveArgTypeModifiers = $this->getConsolidatedDirectiveArgTypeModifiers($relationalTypeResolver, $directiveArgName);
            $enumConsolidatedDirectiveArgNamesIsArrayOfArrays[$directiveArgName]  = $consolidatedDirectiveArgTypeModifiers & SchemaTypeModifiers::IS_ARRAY_OF_ARRAYS;
            $enumConsolidatedDirectiveArgNamesIsArray[$directiveArgName]  = $consolidatedDirectiveArgTypeModifiers & SchemaTypeModifiers::IS_ARRAY;
        }
        [$maybeErrors, $maybeDeprecations] = $this->validateEnumFieldOrDirectiveArguments(
            $enumConsolidatedDirectiveArgNameTypeResolvers,
            $enumConsolidatedDirectiveArgNamesIsArrayOfArrays,
            $enumConsolidatedDirectiveArgNamesIsArray,
            $directiveName,
            $directiveArgs,
            ResolverTypes::DIRECTIVE
        );
        $directiveDeprecationMessages = $maybeDeprecations;

        return $directiveDeprecationMessages;
    }

    public function getDirectiveWarningDescription(RelationalTypeResolverInterface $relationalTypeResolver): ?string
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($relationalTypeResolver);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getDirectiveWarningDescription($relationalTypeResolver);
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

    public function resolveDirectiveWarningDescription(RelationalTypeResolverInterface $relationalTypeResolver): ?string
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
                    return sprintf(
                        $this->translationAPI->__('The DirectiveResolver used to process directive \'%s\' (which has version \'%s\') does not pay attention to the version constraint; hence, argument \'versionConstraint\', with value \'%s\', was ignored', 'component-model'),
                        $this->getDirectiveName(),
                        $this->getDirectiveVersion($relationalTypeResolver) ?? '',
                        $versionConstraint
                    );
                }
            }
        }
        return $this->getDirectiveWarningDescription($relationalTypeResolver);
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
        // $pipelineIDsDataFields is an array containing all stages of the pipe
        // The one corresponding to the current stage is at the head. Take it out from there,
        // and keep passing down the rest of the array to the next stages
        list(
            $relationalTypeResolver,
            $pipelineIDsDataFields,
            $pipelineDirectiveResolverInstances,
            $objectIDItems,
            $unionDBKeyIDs,
            $dbItems,
            $previousDBItems,
            $variables,
            $messages,
            $objectErrors,
            $objectWarnings,
            $objectDeprecations,
            $objectNotices,
            $objectTraces,
            $schemaErrors,
            $schemaWarnings,
            $schemaDeprecations,
            $schemaNotices,
            $schemaTraces
        ) = DirectivePipelineUtils::extractArgumentsFromPayload($payload);

        // Extract the head, keep passing down the rest
        $idsDataFields = $pipelineIDsDataFields[0];
        array_shift($pipelineIDsDataFields);
        // The $pipelineDirectiveResolverInstances is the series of directives executed in the pipeline
        // The current stage is at the head. Remove it
        array_shift($pipelineDirectiveResolverInstances);

        // // 2. Validate operation
        // $this->validateDirective(
        //     $relationalTypeResolver,
        //     $idsDataFields,
        //     $pipelineIDsDataFields,
        //     $pipelineDirectiveResolverInstances,
        //     $objectIDItems,
        //     $dbItems,
        //     $previousDBItems,
        //     $variables,
        //     $messages,
        //     $objectErrors,
        //     $objectWarnings,
        //     $objectDeprecations,
        //     $objectNotices,
        //     $objectTraces,
        //     $schemaErrors,
        //     $schemaWarnings,
        //     $schemaDeprecations,
        //     $schemaNotices,
        //     $schemaTraces
        // );

        // 2. Execute operation.
        // First check that if the validation took away the elements, and so the directive can't execute anymore
        // For instance, executing ?query=posts.id|title<default,translate(from:en,to:es)> will fail
        // after directive "default", so directive "translate" must not even execute
        if (!$this->needsIDsDataFieldsToExecute() || $this->hasIDsDataFields($idsDataFields)) {
            // If the directive resolver throws an Exception,
            // catch it and add objectErrors
            try {
                $this->resolveDirective(
                    $relationalTypeResolver,
                    $idsDataFields,
                    $pipelineIDsDataFields,
                    $pipelineDirectiveResolverInstances,
                    $objectIDItems,
                    $unionDBKeyIDs,
                    $dbItems,
                    $previousDBItems,
                    $variables,
                    $messages,
                    $objectErrors,
                    $objectWarnings,
                    $objectDeprecations,
                    $objectNotices,
                    $objectTraces,
                    $schemaErrors,
                    $schemaWarnings,
                    $schemaDeprecations,
                    $schemaNotices,
                    $schemaTraces
                );
            } catch (Exception $e) {
                if (RootEnvironment::isApplicationEnvironmentDev()) {
                    throw $e;
                }
                $failureMessage = sprintf(
                    $this->translationAPI->__('Resolving directive \'%s\' produced an exception, with message: \'%s\'', 'component-model'),
                    $this->directive,
                    $e->getMessage()
                );
                $this->processFailure(
                    $relationalTypeResolver,
                    $failureMessage,
                    [],
                    $idsDataFields,
                    $pipelineIDsDataFields,
                    $objectIDItems,
                    $dbItems,
                    $objectErrors,
                    $objectWarnings
                );
            }
        }

        // 3. Re-create the payload from the modified variables
        return DirectivePipelineUtils::convertArgumentsToPayload(
            $relationalTypeResolver,
            $pipelineIDsDataFields,
            $pipelineDirectiveResolverInstances,
            $objectIDItems,
            $unionDBKeyIDs,
            $dbItems,
            $previousDBItems,
            $variables,
            $messages,
            $objectErrors,
            $objectWarnings,
            $objectDeprecations,
            $objectNotices,
            $objectTraces,
            $schemaErrors,
            $schemaWarnings,
            $schemaDeprecations,
            $schemaNotices,
            $schemaTraces
        );
    }

    /**
     * Depending on environment configuration, either show a warning,
     * or show an error and remove the fields from the directive pipeline for further execution
     */
    protected function processFailure(
        RelationalTypeResolverInterface $relationalTypeResolver,
        string $failureMessage,
        array $failedFields,
        array &$idsDataFields,
        array &$succeedingPipelineIDsDataFields,
        array &$objectIDItems,
        array &$dbItems,
        array &$objectErrors,
        array &$objectWarnings
    ): void {
        $allFieldsFailed = empty($failedFields);
        if ($allFieldsFailed) {
            // Remove all fields
            $idsDataFieldsToRemove = $idsDataFields;
            // Calculate which fields are being removed, to add to the error
            foreach ($idsDataFields as $id => &$data_fields) {
                $failedFields = array_merge(
                    $failedFields,
                    $data_fields['direct']
                );
            }
            $failedFields = array_values(array_unique($failedFields));
        } else {
            $idsDataFieldsToRemove = [];
            // Calculate which fields to remove
            foreach ($idsDataFields as $id => &$data_fields) {
                $idsDataFieldsToRemove[(string)$id]['direct'] = array_intersect(
                    $data_fields['direct'],
                    $failedFields
                );
            }
        }
        // If the failure must be processed as an error, we must also remove the fields from the directive pipeline
        $removeFieldIfDirectiveFailed = ComponentConfiguration::removeFieldIfDirectiveFailed();
        if ($removeFieldIfDirectiveFailed) {
            $this->removeIDsDataFields(
                $idsDataFieldsToRemove,
                $succeedingPipelineIDsDataFields
            );
        }
        $setFailingFieldResponseAsNull = ComponentConfiguration::setFailingFieldResponseAsNull();
        if ($setFailingFieldResponseAsNull) {
            $this->setIDsDataFieldsAsNull(
                $relationalTypeResolver,
                $idsDataFieldsToRemove,
                $dbItems,
                $objectIDItems,
            );
        }

        // Show the failureMessage either as error or as warning
        $directiveName = $this->getDirectiveName();
        if ($setFailingFieldResponseAsNull) {
            foreach ($idsDataFieldsToRemove as $id => $dataFields) {
                foreach ($dataFields['direct'] as $failedField) {
                    $objectErrors[(string)$id][] = [
                        Tokens::PATH => [$failedField, $this->directive],
                        Tokens::MESSAGE => $failureMessage,
                    ];
                }
            }
        } elseif ($removeFieldIfDirectiveFailed) {
            if (count($failedFields) == 1) {
                $message = $this->translationAPI->__('%s. Field \'%s\' has been removed from the directive pipeline', 'component-model');
            } else {
                $message = $this->translationAPI->__('%s. Fields \'%s\' have been removed from the directive pipeline', 'component-model');
            }
            foreach ($idsDataFieldsToRemove as $id => $dataFields) {
                foreach ($dataFields['direct'] as $failedField) {
                    $objectErrors[(string)$id][] = [
                        Tokens::PATH => [$failedField, $this->directive],
                        Tokens::MESSAGE => sprintf(
                            $message,
                            $failureMessage,
                            implode($this->translationAPI->__('\', \''), $failedFields)
                        ),
                    ];
                }
            }
        } else {
            if (count($failedFields) === 1) {
                $message = $this->translationAPI->__('%s. Execution of directive \'%s\' has been ignored on field \'%s\'', 'component-model');
            } else {
                $message = $this->translationAPI->__('%s. Execution of directive \'%s\' has been ignored on fields \'%s\'', 'component-model');
            }
            foreach ($idsDataFieldsToRemove as $id => $dataFields) {
                foreach ($dataFields['direct'] as $failedField) {
                    $objectWarnings[(string)$id][] = [
                        Tokens::PATH => [$failedField, $this->directive],
                        Tokens::MESSAGE => sprintf(
                            $message,
                            $failureMessage,
                            $directiveName,
                            implode($this->translationAPI->__('\', \''), $failedFields)
                        ),
                    ];
                }
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
         * `DangerouslyDynamic` is a special scalar type which is not coerced or validated.
         * In particular, it does not need to validate if it is an array or not,
         * as according to the applied WrappingType.
         *
         * If disabled, then do not expose the directive if it
         * has any mandatory argument of type `DangerouslyDynamic`
         */
        if (ComponentConfiguration::skipExposingDangerouslyDynamicScalarTypeInSchema()) {
            /**
             * If `DangerouslyDynamic` is disabled, do not expose the field if either:
             *
             *   1. its type is `DangerouslyDynamic`
             *   2. it has any mandatory argument of type `DangerouslyDynamic`
             */
            $consolidatedDirectiveArgNameTypeResolvers = $this->getConsolidatedDirectiveArgNameTypeResolvers($relationalTypeResolver);
            $consolidatedDirectiveArgsTypeModifiers = [];
            foreach (array_keys($consolidatedDirectiveArgNameTypeResolvers) as $directiveArgName) {
                $consolidatedDirectiveArgsTypeModifiers[$directiveArgName] = $this->getConsolidatedDirectiveArgTypeModifiers($relationalTypeResolver, $directiveArgName);
            }
            if (
                $this->hasMandatoryDangerouslyDynamicScalarInputType(
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
            $directiveName = $this->getDirectiveName();
            $schemaDefinition = [
                SchemaDefinition::NAME => $directiveName,
                SchemaDefinition::DIRECTIVE_TYPE => $this->getDirectiveType(),
                SchemaDefinition::DIRECTIVE_PIPELINE_POSITION => $this->getPipelinePosition(),
                SchemaDefinition::DIRECTIVE_IS_REPEATABLE => $this->isRepeatable(),
                SchemaDefinition::DIRECTIVE_IS_GLOBAL => $this->isGlobal($relationalTypeResolver),
                SchemaDefinition::DIRECTIVE_NEEDS_DATA_TO_EXECUTE => $this->needsIDsDataFieldsToExecute(),
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
            if ($extensions = $this->getDirectiveSchemaDefinitionExtensions($relationalTypeResolver)) {
                $schemaDefinition[SchemaDefinition::EXTENSIONS] = $extensions;
            }
            /**
             * Please notice: the version always comes from the directiveResolver, and not from the schemaDefinitionResolver
             * That is because it is the implementer the one who knows what version it is, and not the one defining the interface
             * If the interface changes, the implementer will need to change, so the version will be upgraded
             * But it could also be that the contract doesn't change, but the implementation changes
             * it's really not their responsibility
             */
            if (Environment::enableSemanticVersionConstraints()) {
                if ($version = $this->getDirectiveVersion($relationalTypeResolver)) {
                    $schemaDefinition[SchemaDefinition::VERSION] = $version;
                }
            }
            $this->schemaDefinitionForDirectiveCache[$key] = $schemaDefinition;
        }
        return $this->schemaDefinitionForDirectiveCache[$key];
    }

    public function getDirectiveSchemaDefinitionExtensions(RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        return [];
    }
}
