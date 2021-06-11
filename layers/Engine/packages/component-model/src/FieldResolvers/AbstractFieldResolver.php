<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers;

use Exception;
use Composer\Semver\Semver;
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
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\Resolvers\FieldOrDirectiveResolverTrait;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\AttachableExtensions\AttachableExtensionTrait;
use PoP\ComponentModel\FieldResolvers\FieldSchemaDefinitionResolverTrait;
use PoP\ComponentModel\Resolvers\InterfaceSchemaDefinitionResolverAdapter;
use PoP\ComponentModel\FieldResolvers\FieldSchemaDefinitionResolverInterface;
use PoP\ComponentModel\FieldInterfaceResolvers\FieldInterfaceResolverInterface;
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

    function __construct(
        protected TranslationAPIInterface $translationAPI,
        protected HooksAPIInterface $hooksAPI,
        protected InstanceManagerInterface $instanceManager,
        protected FieldQueryInterpreterInterface $fieldQueryInterpreter,
        protected NameResolverInterface $nameResolver,
        protected CMSServiceInterface $cmsService,
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

    public function isGlobal(TypeResolverInterface $typeResolver, string $fieldName): bool
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
    public function decideCanProcessBasedOnVersionConstraint(TypeResolverInterface $typeResolver): bool
    {
        return false;
    }

    /**
     * Indicates if the fieldResolver can process this combination of fieldName and fieldArgs
     * It is required to support a multiverse of fields: different fieldResolvers can resolve the field, based on the required version (passed through $fieldArgs['branch'])
     *
     * @param array<string, mixed> $fieldArgs
     */
    public function resolveCanProcess(TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []): bool
    {
        /** Check if to validate the version */
        if (
            Environment::enableSemanticVersionConstraints() &&
            $this->decideCanProcessBasedOnVersionConstraint($typeResolver)
        ) {
            /**
             * Please notice: we can get the fieldVersion directly from this instance,
             * and not from the schemaDefinition, because the version is set at the FieldResolver level,
             * and not the FieldInterfaceResolver, which is the other entity filling data
             * inside the schemaDefinition object.
             * If this field is tagged with a version...
             */
            if ($schemaFieldVersion = $this->getSchemaFieldVersion($typeResolver, $fieldName)) {
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
                        $typeResolver->getNamespacedTypeName(),
                        $fieldName
                    )
                    ?? VersioningHelpers::getVersionConstraintsForField(
                        $typeResolver->getTypeName(),
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
                    return Semver::satisfies($schemaFieldVersion, $versionConstraint);
                } catch (Exception) {
                    return false;
                }
            }
        }
        return true;
    }
    public function resolveSchemaValidationErrorDescriptions(TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []): ?array
    {
        $fieldSchemaDefinition = $this->getSchemaDefinitionForField($typeResolver, $fieldName, $fieldArgs);
        if ($fieldArgsSchemaDefinition = $fieldSchemaDefinition[SchemaDefinition::ARGNAME_ARGS] ?? null) {
            /**
             * Validate mandatory values
             */
            if (
                $maybeError = $this->maybeValidateNotMissingFieldOrDirectiveArguments(
                    $typeResolver,
                    $fieldName,
                    $fieldArgs,
                    $fieldArgsSchemaDefinition,
                    ResolverTypes::FIELD
                )
            ) {
                return [$maybeError];
            }

            if ($this->canValidateFieldOrDirectiveArgumentsWithValuesForSchema($fieldArgs)) {
                /**
                 * Validate array types are provided as arrays
                 */
                if (
                    $maybeError = $this->maybeValidateArrayTypeFieldOrDirectiveArguments(
                        $typeResolver,
                        $fieldName,
                        $fieldArgs,
                        $fieldArgsSchemaDefinition,
                        ResolverTypes::FIELD
                    )
                ) {
                    return [$maybeError];
                }

                /**
                 * Validate enums
                 */
                list(
                    $maybeError
                ) = $this->maybeValidateEnumFieldOrDirectiveArguments(
                    $typeResolver,
                    $fieldName,
                    $fieldArgs,
                    $fieldArgsSchemaDefinition,
                    ResolverTypes::FIELD
                );
                if ($maybeError) {
                    return [$maybeError];
                }
            }
        }
        // If a MutationResolver is declared, let it resolve the value
        if ($mutationResolverClass = $this->resolveFieldMutationResolverClass($typeResolver, $fieldName)) {
            // Validate on the schema?
            if (!$this->validateMutationOnResultItem($typeResolver, $fieldName)) {
                /** @var MutationResolverInterface */
                $mutationResolver = $this->instanceManager->getInstance($mutationResolverClass);
                return $mutationResolver->validateErrors($fieldArgs);
            }
        }
        return null;
    }
    public function resolveSchemaValidationDeprecationDescriptions(TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []): ?array
    {
        $fieldSchemaDefinition = $this->getSchemaDefinitionForField($typeResolver, $fieldName, $fieldArgs);
        if ($fieldArgsSchemaDefinition = $fieldSchemaDefinition[SchemaDefinition::ARGNAME_ARGS] ?? null) {
            list(
                $maybeError,
                $maybeDeprecation
            ) = $this->maybeValidateEnumFieldOrDirectiveArguments(
                $typeResolver,
                $fieldName,
                $fieldArgs,
                $fieldArgsSchemaDefinition,
                ResolverTypes::FIELD
            );
            if ($maybeDeprecation) {
                return [
                    $maybeDeprecation
                ];
            }
        }
        return null;
    }

    /**
     * Fields may not be directly visible in the schema,
     * eg: because they are used only by the application, and must not
     * be exposed to the user (eg: "accessControlLists")
     */
    public function skipAddingToSchemaDefinition(TypeResolverInterface $typeResolver, string $fieldName): bool
    {
        return false;
    }

    /**
     * Get the "schema" properties as for the fieldName
     */
    public function getSchemaDefinitionForField(TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []): array
    {
        // First check if the value was cached
        $key = $typeResolver->getNamespacedTypeName() . '|' . $fieldName . '|' . json_encode($fieldArgs);
        if (!isset($this->schemaDefinitionForFieldCache[$key])) {
            $schemaDefinition = [
                SchemaDefinition::ARGNAME_NAME => $fieldName,
            ];
            // Find which is the $schemaDefinitionResolver that will satisfy this schema definition
            // First try the one declared by the fieldResolver
            $maybeSchemaDefinitionResolver = $this->getSchemaDefinitionResolver($typeResolver);
            if (
                $maybeSchemaDefinitionResolver !== null
                && in_array($fieldName, $maybeSchemaDefinitionResolver->getFieldNamesToResolve())
            ) {
                $schemaDefinitionResolver = $maybeSchemaDefinitionResolver;
            } else {
                // Otherwise, try through all of its interfaces
                foreach ($this->getFieldInterfaceResolvers() as $fieldInterfaceResolver) {
                    if (in_array($fieldName, $fieldInterfaceResolver->getFieldNamesToImplement())) {
                        // Interfaces do not receive the typeResolver, so we must bridge it
                        $schemaDefinitionResolver = new InterfaceSchemaDefinitionResolverAdapter(
                            $fieldInterfaceResolver
                        );
                        break;
                    }
                }
            }

            // If we found a resolver for this fieldName, get all its properties from it
            if ($schemaDefinitionResolver) {
                $schemaDefinition[SchemaDefinition::ARGNAME_TYPE] = $schemaDefinitionResolver->getSchemaFieldType($typeResolver, $fieldName);
                // Use bitwise operators to extract the applied modifiers
                // @see https://www.php.net/manual/en/language.operators.bitwise.php#91291
                $schemaTypeModifiers = $schemaDefinitionResolver->getSchemaFieldTypeModifiers($typeResolver, $fieldName);
                if ($schemaTypeModifiers & SchemaTypeModifiers::NON_NULLABLE) {
                    $schemaDefinition[SchemaDefinition::ARGNAME_NON_NULLABLE] = true;
                }
                if ($schemaTypeModifiers & SchemaTypeModifiers::IS_ARRAY) {
                    $schemaDefinition[SchemaDefinition::ARGNAME_IS_ARRAY] = true;
                }
                if ($schemaTypeModifiers & SchemaTypeModifiers::MAY_BE_ARRAY) {
                    $schemaDefinition[SchemaDefinition::ARGNAME_MAY_BE_ARRAY] = true;
                }
                if ($description = $schemaDefinitionResolver->getSchemaFieldDescription($typeResolver, $fieldName)) {
                    $schemaDefinition[SchemaDefinition::ARGNAME_DESCRIPTION] = $description;
                }
                if ($deprecationDescription = $schemaDefinitionResolver->getSchemaFieldDeprecationDescription($typeResolver, $fieldName, $fieldArgs)) {
                    $schemaDefinition[SchemaDefinition::ARGNAME_DEPRECATED] = true;
                    $schemaDefinition[SchemaDefinition::ARGNAME_DEPRECATIONDESCRIPTION] = $deprecationDescription;
                }
                if ($args = $schemaDefinitionResolver->getFilteredSchemaFieldArgs($typeResolver, $fieldName)) {
                    // Add the args under their name.
                    // Watch out: the name is mandatory!
                    // If it hasn't been set, then skip the entry
                    $nameArgs = [];
                    foreach ($args as $arg) {
                        if (!isset($arg[SchemaDefinition::ARGNAME_NAME])) {
                            continue;
                        }
                        $nameArgs[$arg[SchemaDefinition::ARGNAME_NAME]] = $arg;
                    }
                    $schemaDefinition[SchemaDefinition::ARGNAME_ARGS] = $nameArgs;
                }
                $schemaDefinitionResolver->addSchemaDefinitionForField($schemaDefinition, $typeResolver, $fieldName);
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
                if ($version = $this->getSchemaFieldVersion($typeResolver, $fieldName)) {
                    $schemaDefinition[SchemaDefinition::ARGNAME_VERSION] = $version;
                }
            }
            if (!is_null($this->resolveFieldTypeResolverClass($typeResolver, $fieldName))) {
                $schemaDefinition[SchemaDefinition::ARGNAME_RELATIONAL] = true;
            }
            if (!is_null($this->resolveFieldMutationResolverClass($typeResolver, $fieldName))) {
                $schemaDefinition[SchemaDefinition::ARGNAME_FIELD_IS_MUTATION] = true;
            }

            // Hook to override the values, eg: by the Field Deprecation List
            // 1. Applied on the type
            $hookName = HookHelpers::getSchemaDefinitionForFieldHookName(
                get_class($typeResolver),
                $fieldName
            );
            $schemaDefinition = $this->hooksAPI->applyFilters(
                $hookName,
                $schemaDefinition,
                $typeResolver,
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
                        $typeResolver,
                        $fieldName,
                        $fieldArgs
                    );
                }
            }
            $this->schemaDefinitionForFieldCache[$key] = $schemaDefinition;
        }
        return $this->schemaDefinitionForFieldCache[$key];
    }

    public function enableOrderedSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): bool
    {
        return true;
    }

    public function getSchemaFieldVersion(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        return null;
    }

    protected function hasSchemaFieldVersion(TypeResolverInterface $typeResolver, string $fieldName): bool
    {
        return !empty($this->getSchemaFieldVersion($typeResolver, $fieldName));
    }

    public function resolveSchemaValidationWarningDescriptions(TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []): ?array
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
                if (!$this->decideCanProcessBasedOnVersionConstraint($typeResolver)) {
                    $warnings[] = sprintf(
                        $this->translationAPI->__('The FieldResolver used to process field with name \'%s\' (which has version \'%s\') does not pay attention to the version constraint; hence, argument \'versionConstraint\', with value \'%s\', was ignored', 'component-model'),
                        $fieldName,
                        $this->getSchemaFieldVersion($typeResolver, $fieldName) ?? '',
                        $versionConstraint
                    );
                }
            }
        }
        // If a MutationResolver is declared, let it resolve the value
        if ($mutationResolverClass = $this->resolveFieldMutationResolverClass($typeResolver, $fieldName)) {
            /** @var MutationResolverInterface */
            $mutationResolver = $this->instanceManager->getInstance($mutationResolverClass);
            return $mutationResolver->validateWarnings($fieldArgs);
        }
        return $warnings;
    }

    protected function getFieldArgumentsSchemaDefinitions(TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []): array
    {
        return [];
    }

    /**
     * @param array<string, mixed> $fieldArgs
     */
    public function resolveCanProcessResultItem(
        TypeResolverInterface $typeResolver,
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
        TypeResolverInterface $typeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = []
    ): ?array {
        // Check that mutations can be executed
        if ($this->resolveFieldMutationResolverClass($typeResolver, $fieldName)) {
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
        TypeResolverInterface $typeResolver,
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
        TypeResolverInterface $typeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = []
    ): ?array {
        // Can perform validation through checkpoints
        if ($checkpoints = $this->getValidationCheckpoints($typeResolver, $resultItem, $fieldName, $fieldArgs)) {
            $engine = EngineFacade::getInstance();
            $validation = $engine->validateCheckpoints($checkpoints);
            if (GeneralUtils::isError($validation)) {
                $error = $validation;
                $errorMessage = $error->getErrorMessage();
                if (!$errorMessage) {
                    $errorMessage = sprintf(
                        $this->translationAPI->__('Validation with code \'%s\' failed', 'component-model'),
                        $error->getErrorCode()
                    );
                }
                // Allow to customize the error message for the failing entity
                return [
                    $this->getValidationCheckpointsErrorMessage($error, $errorMessage, $typeResolver, $resultItem, $fieldName, $fieldArgs)
                ];
            }
        }

        // If a MutationResolver is declared, let it resolve the value
        if ($mutationResolverClass = $this->resolveFieldMutationResolverClass($typeResolver, $fieldName)) {
            // Validate on the resultItem?
            if ($this->validateMutationOnResultItem($typeResolver, $fieldName)) {
                /** @var MutationResolverInterface */
                $mutationResolver = $this->instanceManager->getInstance($mutationResolverClass);
                $mutationFieldArgs = $this->getFieldArgsToExecuteMutation(
                    $fieldArgs,
                    $typeResolver,
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
        TypeResolverInterface $typeResolver,
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
        TypeResolverInterface $typeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        // If a MutationResolver is declared, let it resolve the value
        if ($mutationResolverClass = $this->resolveFieldMutationResolverClass($typeResolver, $fieldName)) {
            /** @var MutationResolverInterface */
            $mutationResolver = $this->instanceManager->getInstance($mutationResolverClass);
            $mutationFieldArgs = $this->getFieldArgsToExecuteMutation(
                $fieldArgs,
                $typeResolver,
                $resultItem,
                $fieldName
            );
            return $mutationResolver->execute($mutationFieldArgs);
        }
        return null;
    }

    protected function getFieldArgsToExecuteMutation(
        array $fieldArgs,
        TypeResolverInterface $typeResolver,
        object $resultItem,
        string $fieldName
    ): array {
        return $fieldArgs;
    }

    public function resolveFieldTypeResolverClass(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        return null;
    }

    public function resolveFieldMutationResolverClass(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        return null;
    }
}
