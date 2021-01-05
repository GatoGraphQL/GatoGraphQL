<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers;

use Exception;
use Composer\Semver\Semver;
use PoP\ComponentModel\Environment;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\Schema\HookHelpers;
use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\Resolvers\ResolverTypes;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\Facades\Engine\EngineFacade;
use PoP\ComponentModel\Versioning\VersioningHelpers;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\Resolvers\FieldOrDirectiveResolverTrait;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\AttachableExtensions\AttachableExtensionTrait;
use PoP\ComponentModel\CheckpointSets\CheckpointSets;
use PoP\ComponentModel\FieldResolvers\FieldSchemaDefinitionResolverTrait;
use PoP\ComponentModel\Resolvers\InterfaceSchemaDefinitionResolverAdapter;
use PoP\ComponentModel\FieldResolvers\FieldSchemaDefinitionResolverInterface;
use PoP\ComponentModel\ErrorHandling\Error;

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

    public static function getImplementedInterfaceClasses(): array
    {
        return [];
    }

    /**
     * Implement all the fieldNames defined in the interfaces
     *
     * @return array
     */
    public static function getFieldNamesFromInterfaces(): array
    {
        $fieldNames = [];

        // Iterate classes from the current class towards the parent classes until finding typeResolver that satisfies processing this field
        foreach (self::getInterfaceClasses() as $interfaceClass) {
            $fieldNames = array_merge(
                $fieldNames,
                $interfaceClass::getFieldNamesToImplement()
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
     * @return array
     */
    public static function getInterfaceClasses(): array
    {
        $interfaces = [];

        // Iterate classes from the current class towards the parent classes until finding typeResolver that satisfies processing this field
        $class = get_called_class();
        do {
            $interfaces = array_merge(
                $interfaces,
                $class::getImplementedInterfaceClasses()
            );
            // Otherwise, continue iterating for the class parents
        } while ($class = get_parent_class($class));

        return array_values(array_unique($interfaces));
    }

    /**
     * Define if to use the version to decide if to process the field or not
     *
     * @param TypeResolverInterface $typeResolver
     * @return boolean
     */
    public function decideCanProcessBasedOnVersionConstraint(TypeResolverInterface $typeResolver): bool
    {
        return false;
    }

    /**
     * Indicates if the fieldResolver can process this combination of fieldName and fieldArgs
     * It is required to support a multiverse of fields: different fieldResolvers can resolve the field, based on the required version (passed through $fieldArgs['branch'])
     *
     * @param string $fieldName
     * @param array<string, mixed> $fieldArgs
     * @return boolean
     */
    public function resolveCanProcess(TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []): bool
    {
        /** Check if to validate the version */
        if (Environment::enableSemanticVersionConstraints() &&
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
                } catch (Exception $e) {
                    return false;
                }
            }
        }
        return true;
    }
    public function resolveSchemaValidationErrorDescriptions(TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []): ?array
    {
        $fieldSchemaDefinition = $this->getSchemaDefinitionForField($typeResolver, $fieldName, $fieldArgs);
        if ($schemaFieldArgs = $fieldSchemaDefinition[SchemaDefinition::ARGNAME_ARGS] ?? null) {
            /**
             * Validate mandatory values
             */
            if ($maybeError = $this->maybeValidateNotMissingFieldOrDirectiveArguments(
                $typeResolver,
                $fieldName,
                $fieldArgs,
                $schemaFieldArgs,
                ResolverTypes::FIELD
            )) {
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
                $schemaFieldArgs,
                ResolverTypes::FIELD
            );
            if ($maybeError) {
                return [$maybeError];
            }
        }
        // If a MutationResolver is declared, let it resolve the value
        if ($mutationResolverClass = $this->resolveFieldMutationResolverClass($typeResolver, $fieldName)) {
            // Validate on the schema?
            if (!$this->validateMutationOnResultItem($typeResolver, $fieldName)) {
                $instanceManager = InstanceManagerFacade::getInstance();
                /** @var MutationResolverInterface */
                $mutationResolver = $instanceManager->getInstance($mutationResolverClass);
                return $mutationResolver->validateErrors($fieldArgs);
            }
        }
        return null;
    }
    public function resolveSchemaValidationDeprecationDescriptions(TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []): ?array
    {
        $fieldSchemaDefinition = $this->getSchemaDefinitionForField($typeResolver, $fieldName, $fieldArgs);
        if ($schemaFieldArgs = $fieldSchemaDefinition[SchemaDefinition::ARGNAME_ARGS] ?? null) {
            list(
                $maybeError,
                $maybeDeprecation
            ) = $this->maybeValidateEnumFieldOrDirectiveArguments(
                $typeResolver,
                $fieldName,
                $fieldArgs,
                $schemaFieldArgs,
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
     *
     * @param TypeResolverInterface $typeResolver
     * @param string $fieldName
     * @return boolean
     */
    public function skipAddingToSchemaDefinition(TypeResolverInterface $typeResolver, string $fieldName): bool
    {
        return false;
    }

    /**
     * Get the "schema" properties as for the fieldName
     *
     * @return array
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
            if (!is_null($maybeSchemaDefinitionResolver) && in_array($fieldName, $maybeSchemaDefinitionResolver::getFieldNamesToResolve())) {
                $schemaDefinitionResolver = $maybeSchemaDefinitionResolver;
            } else {
                // Otherwise, try through all of its interfaces
                $instanceManager = InstanceManagerFacade::getInstance();
                foreach (self::getInterfaceClasses() as $interfaceClass) {
                    if (in_array($fieldName, $interfaceClass::getFieldNamesToImplement())) {
                        // Interfaces do not receive the typeResolver, so we must bridge it
                        $schemaDefinitionResolver = new InterfaceSchemaDefinitionResolverAdapter(
                            $instanceManager->getInstance($interfaceClass)
                        );
                        break;
                    }
                }
            }

            // If we found a resolver for this fieldName, get all its properties from it
            if ($schemaDefinitionResolver) {
                if ($type = $schemaDefinitionResolver->getSchemaFieldType($typeResolver, $fieldName)) {
                    $schemaDefinition[SchemaDefinition::ARGNAME_TYPE] = $type;
                }
                if ($schemaDefinitionResolver->isSchemaFieldResponseNonNullable($typeResolver, $fieldName)) {
                    $schemaDefinition[SchemaDefinition::ARGNAME_NON_NULLABLE] = true;
                }
                if ($description = $schemaDefinitionResolver->getSchemaFieldDescription($typeResolver, $fieldName)) {
                    $schemaDefinition[SchemaDefinition::ARGNAME_DESCRIPTION] = $description;
                }
                if ($deprecationDescription = $schemaDefinitionResolver->getSchemaFieldDeprecationDescription($typeResolver, $fieldName, $fieldArgs)) {
                    $schemaDefinition[SchemaDefinition::ARGNAME_DEPRECATED] = true;
                    $schemaDefinition[SchemaDefinition::ARGNAME_DEPRECATIONDESCRIPTION] = $deprecationDescription;
                }
                if ($args = $schemaDefinitionResolver->getFilteredSchemaFieldArgs($typeResolver, $fieldName)) {
                    // Add the args under their name
                    $nameArgs = [];
                    foreach ($args as $arg) {
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
            $hooksAPI = HooksAPIFacade::getInstance();
            $hookName = HookHelpers::getSchemaDefinitionForFieldHookName(
                get_class($typeResolver),
                $fieldName
            );
            $schemaDefinition = $hooksAPI->applyFilters(
                $hookName,
                $schemaDefinition,
                $typeResolver,
                $fieldName,
                $fieldArgs
            );
            // 2. Applied on each of the implemented interfaces
            foreach (self::getInterfaceClasses() as $interfaceClass) {
                if (in_array($fieldName, $interfaceClass::getFieldNamesToImplement())) {
                    $hookName = HookHelpers::getSchemaDefinitionForFieldHookName(
                        $interfaceClass,
                        $fieldName
                    );
                    $schemaDefinition = $hooksAPI->applyFilters(
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
        $translationAPI = TranslationAPIFacade::getInstance();
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
                        $translationAPI->__('The FieldResolver used to process field with name \'%s\' (which has version \'%s\') does not pay attention to the version constraint; hence, argument \'versionConstraint\', with value \'%s\', was ignored', 'component-model'),
                        $fieldName,
                        $this->getSchemaFieldVersion($typeResolver, $fieldName) ?? '',
                        $versionConstraint
                    );
                }
            }
        }
        // If a MutationResolver is declared, let it resolve the value
        if ($mutationResolverClass = $this->resolveFieldMutationResolverClass($typeResolver, $fieldName)) {
            $instanceManager = InstanceManagerFacade::getInstance();
            /** @var MutationResolverInterface */
            $mutationResolver = $instanceManager->getInstance($mutationResolverClass);
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
                $translationAPI = TranslationAPIFacade::getInstance();
                $errorMessage = $error->getErrorMessage();
                if (!$errorMessage) {
                    $errorMessage = sprintf(
                        $translationAPI->__('Validation with code \'%s\' failed', 'component-model'),
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
                $instanceManager = InstanceManagerFacade::getInstance();
                /** @var MutationResolverInterface */
                $mutationResolver = $instanceManager->getInstance($mutationResolverClass);
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
     * @return mixed
     */
    public function resolveValue(
        TypeResolverInterface $typeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ) {
        // If a MutationResolver is declared, let it resolve the value
        if ($mutationResolverClass = $this->resolveFieldMutationResolverClass($typeResolver, $fieldName)) {
            $instanceManager = InstanceManagerFacade::getInstance();
            /** @var MutationResolverInterface */
            $mutationResolver = $instanceManager->getInstance($mutationResolverClass);
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
