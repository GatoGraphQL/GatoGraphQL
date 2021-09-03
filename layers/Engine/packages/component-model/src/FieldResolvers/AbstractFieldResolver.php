<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers;

use Exception;
use PoP\Hooks\HooksAPIInterface;
use PoP\ComponentModel\Environment;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\Schema\HookHelpers;
use PoP\ComponentModel\ErrorHandling\Error;
use PoP\Translation\TranslationAPIInterface;
use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\Resolvers\ResolverTypes;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Facades\Engine\EngineFacade;
use PoP\ComponentModel\Versioning\VersioningHelpers;
use PoP\ComponentModel\CheckpointSets\CheckpointSets;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\Resolvers\FieldOrDirectiveResolverTrait;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\AttachableExtensions\AttachableExtensionTrait;
use PoP\ComponentModel\FieldResolvers\FieldSchemaDefinitionResolverTrait;
use PoP\ComponentModel\Resolvers\InterfaceSchemaDefinitionResolverAdapter;
use PoP\ComponentModel\FieldResolvers\FieldSchemaDefinitionResolverInterface;
use PoP\ComponentModel\FieldInterfaceResolvers\FieldInterfaceResolverInterface;
use PoP\ComponentModel\HelperServices\SemverHelperServiceInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\Engine\CMS\CMSServiceInterface;
use PoP\LooseContracts\NameResolverInterface;

abstract class AbstractFieldResolver implements FieldResolverInterface, FieldSchemaDefinitionResolverInterface
{
    /**
     * This class is attached to a TypeResolver
     */
    use AttachableExtensionTrait;
    use FieldSchemaDefinitionResolverTrait;
    use FieldOrDirectiveResolverTrait;

    /**
     * @var array<string, array>
     */
    protected array $schemaDefinitionForFieldCache = [];
    /**
     * @var array<string, FieldSchemaDefinitionResolverInterface|null>
     */
    protected array $schemaDefinitionResolverForFieldCache = [];

    public function __construct(
        protected TranslationAPIInterface $translationAPI,
        protected HooksAPIInterface $hooksAPI,
        protected InstanceManagerInterface $instanceManager,
        protected FieldQueryInterpreterInterface $fieldQueryInterpreter,
        protected NameResolverInterface $nameResolver,
        protected CMSServiceInterface $cmsService,
        protected SemverHelperServiceInterface $semverHelperService,
    ) {
    }

    public function getImplementedFieldInterfaceResolverClasses(): array
    {
        return [];
    }

    public function getAdminFieldNames(): array
    {
        return [];
    }

    /**
     * Implement all the fieldNames defined in the interfaces
     *
     * @return string[]
     */
    public function getFieldNamesFromInterfaces(): array
    {
        $fieldNames = [];

        foreach ($this->getFieldInterfaceResolvers() as $fieldInterfaceResolver) {
            $fieldNames = array_merge(
                $fieldNames,
                $fieldInterfaceResolver->getFieldNamesToImplement()
            );
        }

        return array_values(array_unique($fieldNames));
    }

    public function isGlobal(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): bool
    {
        return false;
    }

    /**
     * Implement all the fieldNames defined in the interfaces
     *
     * @return FieldInterfaceResolverInterface[]
     */
    public function getFieldInterfaceResolvers(): array
    {
        return array_map(
            function (string $class) {
                return $this->instanceManager->getInstance($class);
            },
            $this->getImplementedFieldInterfaceResolverClasses()
        );
    }

    /**
     * Define if to use the version to decide if to process the field or not
     */
    public function decideCanProcessBasedOnVersionConstraint(RelationalTypeResolverInterface $relationalTypeResolver): bool
    {
        return false;
    }

    /**
     * Indicates if the fieldResolver can process this combination of fieldName and fieldArgs
     * It is required to support a multiverse of fields: different fieldResolvers can resolve the field, based on the required version (passed through $fieldArgs['branch'])
     *
     * @param array<string, mixed> $fieldArgs
     */
    public function resolveCanProcess(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName, array $fieldArgs = []): bool
    {
        /** Check if to validate the version */
        if (
            Environment::enableSemanticVersionConstraints() &&
            $this->decideCanProcessBasedOnVersionConstraint($relationalTypeResolver)
        ) {
            /**
             * Please notice: we can get the fieldVersion directly from this instance,
             * and not from the schemaDefinition, because the version is set at the FieldResolver level,
             * and not the FieldInterfaceResolver, which is the other entity filling data
             * inside the schemaDefinition object.
             * If this field is tagged with a version...
             */
            if ($schemaFieldVersion = $this->getSchemaFieldVersion($relationalTypeResolver, $fieldName)) {
                $vars = ApplicationState::getVars();
                /**
                 * Get versionConstraint in this order:
                 * 1. Passed as field argument
                 * 2. Through param `fieldVersionConstraints[$fieldName]`: specific to the namespaced type + field
                 * 3. Through param `fieldVersionConstraints[$fieldName]`: specific to the type + field
                 * 4. Through param `versionConstraint`: applies to all fields and directives in the query
                 */
                $versionConstraint =
                    $fieldArgs[SchemaDefinition::ARGNAME_VERSION_CONSTRAINT]
                    ?? VersioningHelpers::getVersionConstraintsForField(
                        $relationalTypeResolver->getNamespacedTypeName(),
                        $fieldName
                    )
                    ?? VersioningHelpers::getVersionConstraintsForField(
                        $relationalTypeResolver->getTypeName(),
                        $fieldName
                    )
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
                    return $this->semverHelperService->satisfies($schemaFieldVersion, $versionConstraint);
                } catch (Exception) {
                    return false;
                }
            }
        }
        return true;
    }
    public function resolveSchemaValidationErrorDescriptions(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName, array $fieldArgs = []): ?array
    {
        $fieldSchemaDefinition = $this->getSchemaDefinitionForField($relationalTypeResolver, $fieldName, $fieldArgs);
        if ($fieldArgsSchemaDefinition = $fieldSchemaDefinition[SchemaDefinition::ARGNAME_ARGS] ?? null) {
            /**
             * Validate mandatory values. If it produces errors, return immediately
             */
            if (
                $maybeError = $this->validateNotMissingFieldOrDirectiveArguments(
                    $fieldArgsSchemaDefinition,
                    $fieldName,
                    $fieldArgs,
                    ResolverTypes::FIELD
                )
            ) {
                return [$maybeError];
            }

            if ($this->canValidateFieldOrDirectiveArgumentsWithValuesForSchema($fieldArgs)) {
                /**
                 * Validate array types are provided as arrays. If it produces errors, return immediately
                 */
                if (
                    $maybeErrors = $this->validateArrayTypeFieldOrDirectiveArguments(
                        $fieldArgsSchemaDefinition,
                        $fieldName,
                        $fieldArgs,
                        ResolverTypes::FIELD
                    )
                ) {
                    return $maybeErrors;
                }

                // The errors below can be accumulated
                $errors = [];

                /**
                 * Validate enums
                 */
                if (
                    $maybeErrors = $this->validateEnumFieldOrDirectiveArguments(
                        $fieldArgsSchemaDefinition,
                        $fieldName,
                        $fieldArgs,
                        ResolverTypes::FIELD
                    )
                ) {
                    $errors = array_merge(
                        $errors,
                        $maybeErrors
                    );
                }

                /**
                 * Validate field argument constraints
                 */
                if (
                    $maybeErrors = $this->resolveFieldArgumentErrors(
                        $relationalTypeResolver,
                        $fieldName,
                        $fieldArgs
                    )
                ) {
                    $errors = array_merge(
                        $errors,
                        $maybeErrors
                    );
                }

                if ($errors) {
                    return $errors;
                }
            }
        }
        // If a MutationResolver is declared, let it resolve the value
        if ($mutationResolverClass = $this->resolveFieldMutationResolverClass($relationalTypeResolver, $fieldName)) {
            // Validate on the schema?
            if (!$this->validateMutationOnResultItem($relationalTypeResolver, $fieldName)) {
                /** @var MutationResolverInterface */
                $mutationResolver = $this->instanceManager->getInstance($mutationResolverClass);
                return $mutationResolver->validateErrors($fieldArgs);
            }
        }

        // Custom validations
        return $this->doResolveSchemaValidationErrorDescriptions(
            $relationalTypeResolver,
            $fieldName,
            $fieldArgs,
        );
    }

    /**
     * Validate the constraints for the field arguments
     */
    final protected function resolveFieldArgumentErrors(
        RelationalTypeResolverInterface $relationalTypeResolver,
        string $fieldName,
        array $fieldArgs = []
    ): array {
        $errors = [];
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolverForField($relationalTypeResolver, $fieldName);
        if ($schemaDefinitionResolver !== null) {
            foreach ($fieldArgs as $fieldArgName => $fieldArgValue) {
                if (
                    $maybeErrors = $schemaDefinitionResolver->validateFieldArgument(
                        $relationalTypeResolver,
                        $fieldName,
                        $fieldArgName,
                        $fieldArgValue
                    )
                ) {
                    $errors = array_merge(
                        $errors,
                        $maybeErrors
                    );
                }
            }
        }
        return $errors;
    }

    /**
     * Validate the constraints for a field argument
     *
     * @return string[] Error messages
     */
    public function validateFieldArgument(
        RelationalTypeResolverInterface $relationalTypeResolver,
        string $fieldName,
        string $fieldArgName,
        mixed $fieldArgValue
    ): array {
        return [];
    }

    /**
     * Custom validations. Function to override
     */
    protected function doResolveSchemaValidationErrorDescriptions(
        RelationalTypeResolverInterface $relationalTypeResolver,
        string $fieldName,
        array $fieldArgs = []
    ): ?array {
        return null;
    }

    public function resolveSchemaValidationDeprecationDescriptions(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName, array $fieldArgs = []): ?array
    {
        $fieldSchemaDefinition = $this->getSchemaDefinitionForField($relationalTypeResolver, $fieldName, $fieldArgs);
        if ($fieldArgsSchemaDefinition = $fieldSchemaDefinition[SchemaDefinition::ARGNAME_ARGS] ?? null) {
            return $this->getEnumFieldOrDirectiveArgumentDeprecations(
                $fieldArgsSchemaDefinition,
                $fieldName,
                $fieldArgs,
                ResolverTypes::FIELD
            );
        }
        return null;
    }

    /**
     * Fields may not be directly visible in the schema,
     * eg: because they are used only by the application, and must not
     * be exposed to the user (eg: "accessControlLists")
     */
    public function skipAddingToSchemaDefinition(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): bool
    {
        return false;
    }

    /**
     * Get the "schema" properties as for the fieldName
     */
    final public function getSchemaDefinitionForField(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName, array $fieldArgs = []): array
    {
        // First check if the value was cached
        $key = $relationalTypeResolver->getNamespacedTypeName() . '|' . $fieldName . '|' . json_encode($fieldArgs);
        if (!isset($this->schemaDefinitionForFieldCache[$key])) {
            $this->schemaDefinitionForFieldCache[$key] = $this->doGetSchemaDefinitionForField($relationalTypeResolver, $fieldName, $fieldArgs);
        }
        return $this->schemaDefinitionForFieldCache[$key];
    }

    /**
     * Get the "schema" properties as for the fieldName
     */
    final public function doGetSchemaDefinitionForField(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName, array $fieldArgs = []): array
    {
        $schemaDefinition = [
            SchemaDefinition::ARGNAME_NAME => $fieldName,
        ];

        // If we found a resolver for this fieldName, get all its properties from it
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolverForField($relationalTypeResolver, $fieldName);
        if ($schemaDefinitionResolver !== null) {
            $type = $schemaDefinitionResolver->getSchemaFieldType($relationalTypeResolver, $fieldName);
            $schemaDefinition[SchemaDefinition::ARGNAME_TYPE] = $type;
            // Use bitwise operators to extract the applied modifiers
            // @see https://www.php.net/manual/en/language.operators.bitwise.php#91291
            $schemaTypeModifiers = $schemaDefinitionResolver->getSchemaFieldTypeModifiers($relationalTypeResolver, $fieldName);
            if ($schemaTypeModifiers & SchemaTypeModifiers::NON_NULLABLE) {
                $schemaDefinition[SchemaDefinition::ARGNAME_NON_NULLABLE] = true;
            }
            // If setting the "array of arrays" flag, there's no need to set the "array" flag
            $isArrayOfArrays = $schemaTypeModifiers & SchemaTypeModifiers::IS_ARRAY_OF_ARRAYS;
            if (
                $schemaTypeModifiers & SchemaTypeModifiers::IS_ARRAY
                || $isArrayOfArrays
            ) {
                $schemaDefinition[SchemaDefinition::ARGNAME_IS_ARRAY] = true;
                if ($schemaTypeModifiers & SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY) {
                    $schemaDefinition[SchemaDefinition::ARGNAME_IS_NON_NULLABLE_ITEMS_IN_ARRAY] = true;
                }
                if ($isArrayOfArrays) {
                    $schemaDefinition[SchemaDefinition::ARGNAME_IS_ARRAY_OF_ARRAYS] = true;
                    if ($schemaTypeModifiers & SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY_OF_ARRAYS) {
                        $schemaDefinition[SchemaDefinition::ARGNAME_IS_NON_NULLABLE_ITEMS_IN_ARRAY_OF_ARRAYS] = true;
                    }
                }
            }
            if ($description = $schemaDefinitionResolver->getSchemaFieldDescription($relationalTypeResolver, $fieldName)) {
                $schemaDefinition[SchemaDefinition::ARGNAME_DESCRIPTION] = $description;
            }
            if ($deprecationDescription = $schemaDefinitionResolver->getSchemaFieldDeprecationDescription($relationalTypeResolver, $fieldName, $fieldArgs)) {
                $schemaDefinition[SchemaDefinition::ARGNAME_DEPRECATED] = true;
                $schemaDefinition[SchemaDefinition::ARGNAME_DEPRECATIONDESCRIPTION] = $deprecationDescription;
            }
            if ($args = $schemaDefinitionResolver->getSchemaFieldArgs($relationalTypeResolver, $fieldName)) {
                $schemaDefinition[SchemaDefinition::ARGNAME_ARGS] = $this->getFilteredSchemaFieldArgs(
                    $relationalTypeResolver,
                    $fieldName,
                    $args
                );
            }
            $schemaDefinitionResolver->addSchemaDefinitionForField($schemaDefinition, $relationalTypeResolver, $fieldName);
        }
        /**
         * Please notice: the version always comes from the fieldResolver, and not from the schemaDefinitionResolver
         * That is because it is the implementer the one who knows what version it is, and not the one defining the interface
         * If the interface changes, the implementer will need to change, so the version will be upgraded
         * But it could also be that the contract doesn't change, but the implementation changes
         * In particular, Interfaces are schemaDefinitionResolver, but they must not indicate the version...
         * it's really not their responsibility
         */
        if (Environment::enableSemanticVersionConstraints()) {
            if ($version = $this->getSchemaFieldVersion($relationalTypeResolver, $fieldName)) {
                $schemaDefinition[SchemaDefinition::ARGNAME_VERSION] = $version;
            }
        }
        if (!is_null($this->resolveFieldTypeResolverClass($relationalTypeResolver, $fieldName))) {
            $schemaDefinition[SchemaDefinition::ARGNAME_RELATIONAL] = true;
        }
        if (!is_null($this->resolveFieldMutationResolverClass($relationalTypeResolver, $fieldName))) {
            $schemaDefinition[SchemaDefinition::ARGNAME_FIELD_IS_MUTATION] = true;
        }

        // Hook to override the values, eg: by the Field Deprecation List
        // 1. Applied on the type
        $hookName = HookHelpers::getSchemaDefinitionForFieldHookName(
            get_class($relationalTypeResolver),
            $fieldName
        );
        $schemaDefinition = $this->hooksAPI->applyFilters(
            $hookName,
            $schemaDefinition,
            $relationalTypeResolver,
            $fieldName,
            $fieldArgs
        );
        // 2. Applied on each of the implemented interfaces
        foreach ($this->getFieldInterfaceResolvers() as $fieldInterfaceResolver) {
            if (in_array($fieldName, $fieldInterfaceResolver->getFieldNamesToImplement())) {
                $hookName = HookHelpers::getSchemaDefinitionForFieldHookName(
                    get_class($fieldInterfaceResolver),
                    $fieldName
                );
                $schemaDefinition = $this->hooksAPI->applyFilters(
                    $hookName,
                    $schemaDefinition,
                    $relationalTypeResolver,
                    $fieldName,
                    $fieldArgs
                );
            }
        }
        return $schemaDefinition;
    }

    final public function getSchemaDefinitionResolverForField(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?FieldSchemaDefinitionResolverInterface
    {
        // First check if the value was cached
        $key = $relationalTypeResolver->getNamespacedTypeName() . '|' . $fieldName;
        if (!isset($this->schemaDefinitionResolverForFieldCache[$key])) {
            $this->schemaDefinitionResolverForFieldCache[$key] = $this->doGetSchemaDefinitionResolverForField($relationalTypeResolver, $fieldName);
        }
        return $this->schemaDefinitionResolverForFieldCache[$key];
    }

    final public function doGetSchemaDefinitionResolverForField(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?FieldSchemaDefinitionResolverInterface
    {
        // Find which is the $schemaDefinitionResolver that will satisfy this schema definition
        // First try the one declared by the fieldResolver
        $maybeSchemaDefinitionResolver = $this->getSchemaDefinitionResolver($relationalTypeResolver);
        if (
            $maybeSchemaDefinitionResolver !== null
            && in_array($fieldName, $maybeSchemaDefinitionResolver->getFieldNamesToResolve())
        ) {
            return $maybeSchemaDefinitionResolver;
        } else {
            // Otherwise, try through all of its interfaces
            foreach ($this->getFieldInterfaceResolvers() as $fieldInterfaceResolver) {
                if (in_array($fieldName, $fieldInterfaceResolver->getFieldNamesToImplement())) {
                    // Interfaces do not receive the typeResolver, so we must bridge it
                    $interfaceSchemaDefinitionResolverAdapterClass = $this->getInterfaceSchemaDefinitionResolverAdapterClass();
                    return new $interfaceSchemaDefinitionResolverAdapterClass($fieldInterfaceResolver);
                }
            }
        }

        return null;
    }

    protected function getInterfaceSchemaDefinitionResolverAdapterClass(): string
    {
        return InterfaceSchemaDefinitionResolverAdapter::class;
    }

    /**
     * Processes the field args:
     *
     * 1. Adds the version constraint (if enabled)
     * 2. Places all entries under their own name
     * 3. If any entry has no name, it is skipped
     *
     * @return array<string, array>
     */
    protected function getFilteredSchemaFieldArgs(
        RelationalTypeResolverInterface $relationalTypeResolver,
        string $fieldName,
        array $schemaFieldArgs
    ): array {
        /**
         * Add the "versionConstraint" param. Add it at the end, so it doesn't affect the order of params for "orderedSchemaFieldArgs"
         */
        $this->maybeAddVersionConstraintSchemaFieldOrDirectiveArg(
            $schemaFieldArgs,
            $this->hasSchemaFieldVersion($relationalTypeResolver, $fieldName)
        );

        // Add the args under their name. Watch out: the name is mandatory!
        // If it hasn't been set, then skip the entry
        $schemaFieldArgsByName = [];
        foreach ($schemaFieldArgs as $arg) {
            if (!isset($arg[SchemaDefinition::ARGNAME_NAME])) {
                continue;
            }
            $schemaFieldArgsByName[$arg[SchemaDefinition::ARGNAME_NAME]] = $arg;
        }
        return $schemaFieldArgsByName;
    }

    public function enableOrderedSchemaFieldArgs(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): bool
    {
        return true;
    }

    public function getSchemaFieldVersion(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        return null;
    }

    protected function hasSchemaFieldVersion(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): bool
    {
        return !empty($this->getSchemaFieldVersion($relationalTypeResolver, $fieldName));
    }

    public function resolveSchemaValidationWarningDescriptions(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName, array $fieldArgs = []): ?array
    {
        $warnings = [];
        if (Environment::enableSemanticVersionConstraints()) {
            /**
             * If restricting the version, and this fieldResolver doesn't have any version, then show a warning
             */
            if ($versionConstraint = $fieldArgs[SchemaDefinition::ARGNAME_VERSION_CONSTRAINT] ?? null) {
                /**
                 * If this fieldResolver doesn't have versioning, then it accepts everything
                 */
                if (!$this->decideCanProcessBasedOnVersionConstraint($relationalTypeResolver)) {
                    $warnings[] = sprintf(
                        $this->translationAPI->__('The FieldResolver used to process field with name \'%s\' (which has version \'%s\') does not pay attention to the version constraint; hence, argument \'versionConstraint\', with value \'%s\', was ignored', 'component-model'),
                        $fieldName,
                        $this->getSchemaFieldVersion($relationalTypeResolver, $fieldName) ?? '',
                        $versionConstraint
                    );
                }
            }
        }
        // If a MutationResolver is declared, let it resolve the value
        if ($mutationResolverClass = $this->resolveFieldMutationResolverClass($relationalTypeResolver, $fieldName)) {
            /** @var MutationResolverInterface */
            $mutationResolver = $this->instanceManager->getInstance($mutationResolverClass);
            return $mutationResolver->validateWarnings($fieldArgs);
        }
        return $warnings;
    }

    /**
     * @param array<string, mixed> $fieldArgs
     */
    public function resolveCanProcessResultItem(
        RelationalTypeResolverInterface $relationalTypeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = []
    ): bool {
        return true;
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @return array<array>|null A checkpoint set, or null
     */
    protected function getValidationCheckpoints(
        RelationalTypeResolverInterface $relationalTypeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = []
    ): ?array {
        // Check that mutations can be executed
        if ($this->resolveFieldMutationResolverClass($relationalTypeResolver, $fieldName)) {
            return CheckpointSets::CAN_EXECUTE_MUTATIONS;
        }
        return null;
    }

    /**
     * @param array<string, mixed> $fieldArgs
     */
    protected function getValidationCheckpointsErrorMessage(
        Error $error,
        string $errorMessage,
        RelationalTypeResolverInterface $relationalTypeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = []
    ): string {
        return $errorMessage;
    }

    /**
     * @param array<string, mixed> $fieldArgs
     */
    public function getValidationErrorDescriptions(
        RelationalTypeResolverInterface $relationalTypeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = []
    ): ?array {
        // Can perform validation through checkpoints
        if ($checkpoints = $this->getValidationCheckpoints($relationalTypeResolver, $resultItem, $fieldName, $fieldArgs)) {
            $engine = EngineFacade::getInstance();
            $validation = $engine->validateCheckpoints($checkpoints);
            if (GeneralUtils::isError($validation)) {
                $error = $validation;
                $errorMessage = $error->getMessageOrCode();
                // Allow to customize the error message for the failing entity
                return [
                    $this->getValidationCheckpointsErrorMessage($error, $errorMessage, $relationalTypeResolver, $resultItem, $fieldName, $fieldArgs)
                ];
            }
        }

        // If a MutationResolver is declared, let it resolve the value
        if ($mutationResolverClass = $this->resolveFieldMutationResolverClass($relationalTypeResolver, $fieldName)) {
            // Validate on the resultItem?
            if ($this->validateMutationOnResultItem($relationalTypeResolver, $fieldName)) {
                /** @var MutationResolverInterface */
                $mutationResolver = $this->instanceManager->getInstance($mutationResolverClass);
                $mutationFieldArgs = $this->getFieldArgsToExecuteMutation(
                    $fieldArgs,
                    $relationalTypeResolver,
                    $resultItem,
                    $fieldName
                );
                return $mutationResolver->validateErrors($mutationFieldArgs);
            }
        }

        return null;
    }

    /**
     * The mutation can be validated either on the schema (`false`)
     * on on the resultItem (`true`)
     */
    public function validateMutationOnResultItem(
        RelationalTypeResolverInterface $relationalTypeResolver,
        string $fieldName
    ): bool {
        return false;
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        RelationalTypeResolverInterface $relationalTypeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        // If a MutationResolver is declared, let it resolve the value
        if ($mutationResolverClass = $this->resolveFieldMutationResolverClass($relationalTypeResolver, $fieldName)) {
            /** @var MutationResolverInterface */
            $mutationResolver = $this->instanceManager->getInstance($mutationResolverClass);
            $mutationFieldArgs = $this->getFieldArgsToExecuteMutation(
                $fieldArgs,
                $relationalTypeResolver,
                $resultItem,
                $fieldName
            );
            return $mutationResolver->execute($mutationFieldArgs);
        }
        return null;
    }

    protected function getFieldArgsToExecuteMutation(
        array $fieldArgs,
        RelationalTypeResolverInterface $relationalTypeResolver,
        object $resultItem,
        string $fieldName
    ): array {
        return $fieldArgs;
    }

    public function resolveFieldTypeResolverClass(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        return null;
    }

    public function resolveFieldMutationResolverClass(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        return null;
    }
}
