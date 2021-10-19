<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers\ObjectType;

use Exception;
use PoP\ComponentModel\AttachableExtensions\AttachableExtensionTrait;
use PoP\ComponentModel\CheckpointSets\CheckpointSets;
use PoP\ComponentModel\ComponentConfiguration;
use PoP\ComponentModel\Engine\EngineInterface;
use PoP\ComponentModel\Environment;
use PoP\ComponentModel\ErrorHandling\Error;
use PoP\ComponentModel\FieldResolvers\AbstractFieldResolver;
use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldSchemaDefinitionResolverInterface;
use PoP\ComponentModel\HelperServices\SemverHelperServiceInterface;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\Resolvers\FieldOrDirectiveResolverTrait;
use PoP\ComponentModel\Resolvers\FieldOrDirectiveSchemaDefinitionResolverTrait;
use PoP\ComponentModel\Resolvers\InterfaceSchemaDefinitionResolverAdapter;
use PoP\ComponentModel\Resolvers\ResolverTypes;
use PoP\ComponentModel\Resolvers\WithVersionConstraintFieldOrDirectiveResolverTrait;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaDefinitionServiceInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\EnumType\EnumTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\Versioning\VersioningHelpers;
use PoP\Engine\CMS\CMSServiceInterface;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\LooseContracts\NameResolverInterface;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractObjectTypeFieldResolver extends AbstractFieldResolver implements ObjectTypeFieldResolverInterface
{
    use AttachableExtensionTrait;
    use WithVersionConstraintFieldOrDirectiveResolverTrait;
    // Avoid trait collisions for PHP 7.1
    use FieldOrDirectiveResolverTrait, FieldOrDirectiveSchemaDefinitionResolverTrait {
        FieldOrDirectiveSchemaDefinitionResolverTrait::getFieldOrDirectiveArgTypeSchemaDefinition insteadof FieldOrDirectiveResolverTrait;
        FieldOrDirectiveSchemaDefinitionResolverTrait::getTypeSchemaDefinition insteadof FieldOrDirectiveResolverTrait;
        FieldOrDirectiveSchemaDefinitionResolverTrait::processSchemaDefinitionTypeModifiers insteadof FieldOrDirectiveResolverTrait;
        FieldOrDirectiveSchemaDefinitionResolverTrait::autowireFieldOrDirectiveSchemaDefinitionResolverTrait insteadof FieldOrDirectiveResolverTrait;
        FieldOrDirectiveSchemaDefinitionResolverTrait::getFieldTypeSchemaDefinition insteadof FieldOrDirectiveResolverTrait;
        FieldOrDirectiveSchemaDefinitionResolverTrait::isDangerouslyDynamicScalarFieldType insteadof FieldOrDirectiveResolverTrait;
        FieldOrDirectiveSchemaDefinitionResolverTrait::hasMandatoryDangerouslyDynamicScalarInputType insteadof FieldOrDirectiveResolverTrait;
    }

    /**
     * @var array<string, array>
     */
    protected array $schemaDefinitionForFieldCache = [];
    /** @var array<string, string|null> */
    protected array $consolidatedFieldDescriptionCache = [];
    /** @var array<string, string|null> */
    protected array $consolidatedFieldDeprecationMessageCache = [];
    /** @var array<string, array<string, InputTypeResolverInterface>> */
    protected array $consolidatedFieldArgNameTypeResolversCache = [];
    /** @var array<string, string|null> */
    protected array $consolidatedFieldArgDescriptionCache = [];
    /** @var array<string, string|null> */
    protected array $consolidatedFieldArgDeprecationMessageCache = [];
    /** @var array<string, mixed> */
    protected array $consolidatedFieldArgDefaultValueCache = [];
    /** @var array<string, int> */
    protected array $consolidatedFieldArgTypeModifiersCache = [];
    /** @var array<string, array<string, mixed>> */
    protected array $schemaFieldArgsCache = [];
    /**
     * @var array<string, ObjectTypeFieldSchemaDefinitionResolverInterface>
     */
    protected array $interfaceTypeFieldSchemaDefinitionResolverCache = [];

    protected FieldQueryInterpreterInterface $fieldQueryInterpreter;
    protected NameResolverInterface $nameResolver;
    protected CMSServiceInterface $cmsService;
    protected SemverHelperServiceInterface $semverHelperService;
    protected SchemaDefinitionServiceInterface $schemaDefinitionService;
    protected EngineInterface $engine;
    protected StringScalarTypeResolver $stringScalarTypeResolver;

    #[Required]
    final public function autowireAbstractObjectTypeFieldResolver(
        FieldQueryInterpreterInterface $fieldQueryInterpreter,
        NameResolverInterface $nameResolver,
        CMSServiceInterface $cmsService,
        SemverHelperServiceInterface $semverHelperService,
        SchemaDefinitionServiceInterface $schemaDefinitionService,
        EngineInterface $engine,
        StringScalarTypeResolver $stringScalarTypeResolver,
    ): void {
        $this->fieldQueryInterpreter = $fieldQueryInterpreter;
        $this->nameResolver = $nameResolver;
        $this->cmsService = $cmsService;
        $this->semverHelperService = $semverHelperService;
        $this->schemaDefinitionService = $schemaDefinitionService;
        $this->engine = $engine;
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }

    final public function getClassesToAttachTo(): array
    {
        return $this->getObjectTypeResolverClassesToAttachTo();
    }

    public function getImplementedInterfaceTypeFieldResolvers(): array
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
        foreach ($this->getImplementedInterfaceTypeFieldResolvers() as $interfaceTypeFieldResolver) {
            $fieldNames = array_merge(
                $fieldNames,
                $interfaceTypeFieldResolver->getFieldNamesToImplement()
            );
        }
        return array_values(array_unique($fieldNames));
    }

    /**
     * Each InterfaceTypeFieldResolver provides a list of fieldNames to the Interface.
     * The Interface may also accept other fieldNames from other InterfaceTypeFieldResolvers.
     * That's why this function is "partially" implemented: the Interface
     * may be completely implemented or not.
     *
     * @return InterfaceTypeResolverInterface[]
     */
    final public function getPartiallyImplementedInterfaceTypeResolvers(): array
    {
        $interfaceTypeResolvers = [];
        foreach ($this->getImplementedInterfaceTypeFieldResolvers() as $interfaceTypeFieldResolver) {
            // Add under class as to mimick `array_unique` for object
            foreach ($interfaceTypeFieldResolver->getPartiallyImplementedInterfaceTypeResolvers() as $partiallyImplementedInterfaceTypeResolver) {
                $interfaceTypeResolvers[get_class($partiallyImplementedInterfaceTypeResolver)] = $partiallyImplementedInterfaceTypeResolver;
            }
        }
        return array_values($interfaceTypeResolvers);
    }

    /**
     * Return the object implementing the schema definition for this ObjectTypeFieldResolver.
     */
    final protected function getSchemaDefinitionResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ObjectTypeFieldSchemaDefinitionResolverInterface
    {
        $fieldOrInterfaceTypeFieldSchemaDefinitionResolver = $this->doGetSchemaDefinitionResolver($objectTypeResolver, $fieldName);
        if ($fieldOrInterfaceTypeFieldSchemaDefinitionResolver instanceof InterfaceTypeFieldSchemaDefinitionResolverInterface) {
            // Interfaces do not receive the typeResolver, so we must bridge it
            // First check if the class is cached
            $key = $objectTypeResolver->getNamespacedTypeName() . '|' . $fieldName;
            if (isset($this->interfaceTypeFieldSchemaDefinitionResolverCache[$key])) {
                return $this->interfaceTypeFieldSchemaDefinitionResolverCache[$key];
            }
            // Create an Adapter and cache it
            $interfaceTypeFieldSchemaDefinitionResolver = $fieldOrInterfaceTypeFieldSchemaDefinitionResolver;
            $interfaceSchemaDefinitionResolverAdapterClass = $this->getInterfaceSchemaDefinitionResolverAdapterClass();
            $this->interfaceTypeFieldSchemaDefinitionResolverCache[$key] = new $interfaceSchemaDefinitionResolverAdapterClass($interfaceTypeFieldSchemaDefinitionResolver);
            return $this->interfaceTypeFieldSchemaDefinitionResolverCache[$key];
        }
        $fieldSchemaDefinitionResolver = $fieldOrInterfaceTypeFieldSchemaDefinitionResolver;
        return $fieldSchemaDefinitionResolver;
    }

    /**
     * By default, the resolver is this same object, unless function
     * `getInterfaceTypeFieldSchemaDefinitionResolver` is
     * implemented
     */
    protected function doGetSchemaDefinitionResolver(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName
    ): ObjectTypeFieldSchemaDefinitionResolverInterface | InterfaceTypeFieldSchemaDefinitionResolverInterface {
        if ($interfaceTypeFieldSchemaDefinitionResolver = $this->getInterfaceTypeFieldSchemaDefinitionResolver($objectTypeResolver, $fieldName)) {
            /** @var InterfaceTypeFieldSchemaDefinitionResolverInterface */
            return $interfaceTypeFieldSchemaDefinitionResolver;
        }
        return $this;
    }

    /**
     * Retrieve the InterfaceTypeFieldSchemaDefinitionResolverInterface
     * By default, if the ObjectTypeFieldResolver implements an interface,
     * it is used as SchemaDefinitionResolver for the matching fields
     */
    protected function getInterfaceTypeFieldSchemaDefinitionResolver(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName
    ): ?InterfaceTypeFieldResolverInterface {
        foreach ($this->getImplementedInterfaceTypeFieldResolvers() as $implementedInterfaceTypeFieldResolver) {
            if (!in_array($fieldName, $implementedInterfaceTypeFieldResolver->getFieldNamesToImplement())) {
                continue;
            }
            return $implementedInterfaceTypeFieldResolver;
        }
        return null;
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($objectTypeResolver, $fieldName);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getFieldTypeModifiers($objectTypeResolver, $fieldName);
        }
        return SchemaTypeModifiers::NONE;
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($objectTypeResolver, $fieldName);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getFieldDescription($objectTypeResolver, $fieldName);
        }
        return null;
    }

    /**
     * Consolidation of the schema field arguments. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     */
    final public function getConsolidatedFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        // Cache the result
        $cacheKey = $objectTypeResolver::class . '.' . $fieldName;
        if (array_key_exists($cacheKey, $this->consolidatedFieldDescriptionCache)) {
            return $this->consolidatedFieldDescriptionCache[$cacheKey];
        }
        $this->consolidatedFieldDescriptionCache[$cacheKey] = $this->hooksAPI->applyFilters(
            HookNames::OBJECT_TYPE_FIELD_DESCRIPTION,
            $this->getFieldDescription($objectTypeResolver, $fieldName),
            $this,
            $objectTypeResolver,
            $fieldName,
        );
        return $this->consolidatedFieldDescriptionCache[$cacheKey];
    }

    /**
     * Consolidation of the schema field arguments. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     */
    final public function getConsolidatedFieldDeprecationMessage(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        // Cache the result
        $cacheKey = $objectTypeResolver::class . '.' . $fieldName;
        if (array_key_exists($cacheKey, $this->consolidatedFieldDeprecationMessageCache)) {
            return $this->consolidatedFieldDeprecationMessageCache[$cacheKey];
        }
        $this->consolidatedFieldDeprecationMessageCache[$cacheKey] = $this->hooksAPI->applyFilters(
            HookNames::OBJECT_TYPE_FIELD_DEPRECATION_MESSAGE,
            $this->getFieldDeprecationMessage($objectTypeResolver, $fieldName),
            $this,
            $objectTypeResolver,
            $fieldName,
        );
        return $this->consolidatedFieldDeprecationMessageCache[$cacheKey];
    }

    /**
     * @return array<string, InputTypeResolverInterface>
     */
    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($objectTypeResolver, $fieldName);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName);
        }
        return [];
    }

    public function getFieldArgDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): ?string
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($objectTypeResolver, $fieldName);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName);
        }
        // Version constraint (possibly enabled)
        if ($fieldArgName === SchemaDefinition::VERSION_CONSTRAINT) {
            return $this->getVersionConstraintFieldOrDirectiveArgDescription();
        }
        return null;
    }

    public function getFieldArgDefaultValue(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): mixed
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($objectTypeResolver, $fieldName);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getFieldArgDefaultValue($objectTypeResolver, $fieldName, $fieldArgName);
        }
        return null;
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($objectTypeResolver, $fieldName);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName);
        }
        return SchemaTypeModifiers::NONE;
    }

    /**
     * Consolidation of the schema field arguments. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     */
    final public function getConsolidatedFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        // Cache the result
        $cacheKey = $objectTypeResolver::class . '.' . $fieldName;
        if (array_key_exists($cacheKey, $this->consolidatedFieldArgNameTypeResolversCache)) {
            return $this->consolidatedFieldArgNameTypeResolversCache[$cacheKey];
        }

        $fieldArgNameTypeResolvers = $this->getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName);

        /**
         * Allow to override/extend the inputs (eg: module "Post Categories" can add
         * input "categories" to field "Root.createPost")
         */
        $consolidatedFieldArgNameTypeResolvers = $this->hooksAPI->applyFilters(
            HookNames::OBJECT_TYPE_FIELD_ARG_NAME_TYPE_RESOLVERS,
            $fieldArgNameTypeResolvers,
            $this,
            $objectTypeResolver,
            $fieldName,
        );
        if ($consolidatedFieldArgNameTypeResolvers !== []) {
            /**
             * Add the version constraint (if enabled)
             * Only add the argument if this field or directive has a version
             * If it doesn't, then there will only be one version of it,
             * and it can be kept empty for simplicity
             */
            if (Environment::enableSemanticVersionConstraints()) {
                $hasVersion = $this->hasSchemaFieldVersion($objectTypeResolver, $fieldName);
                if ($hasVersion) {
                    $consolidatedFieldArgNameTypeResolvers[SchemaDefinition::VERSION_CONSTRAINT] = $this->stringScalarTypeResolver;
                }
            }
        }
        $this->consolidatedFieldArgNameTypeResolversCache[$cacheKey] = $consolidatedFieldArgNameTypeResolvers;
        return $this->consolidatedFieldArgNameTypeResolversCache[$cacheKey];
    }

    /**
     * Consolidation of the schema field arguments. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     */
    final public function getConsolidatedFieldArgDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): ?string
    {
        // Cache the result
        $cacheKey = $objectTypeResolver::class . '.' . $fieldName . '(' . $fieldArgName . ':)';
        if (array_key_exists($cacheKey, $this->consolidatedFieldArgDescriptionCache)) {
            return $this->consolidatedFieldArgDescriptionCache[$cacheKey];
        }
        $this->consolidatedFieldArgDescriptionCache[$cacheKey] = $this->hooksAPI->applyFilters(
            HookNames::OBJECT_TYPE_FIELD_ARG_DESCRIPTION,
            $this->getFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName),
            $this,
            $objectTypeResolver,
            $fieldName,
            $fieldArgName,
        );
        return $this->consolidatedFieldArgDescriptionCache[$cacheKey];
    }

    /**
     * Consolidation of the schema field arguments. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     */
    final public function getConsolidatedFieldArgDefaultValue(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): mixed
    {
        // Cache the result
        $cacheKey = $objectTypeResolver::class . '.' . $fieldName . '(' . $fieldArgName . ':)';
        if (array_key_exists($cacheKey, $this->consolidatedFieldArgDefaultValueCache)) {
            return $this->consolidatedFieldArgDefaultValueCache[$cacheKey];
        }
        $this->consolidatedFieldArgDefaultValueCache[$cacheKey] = $this->hooksAPI->applyFilters(
            HookNames::OBJECT_TYPE_FIELD_ARG_DEFAULT_VALUE,
            $this->getFieldArgDefaultValue($objectTypeResolver, $fieldName, $fieldArgName),
            $this,
            $objectTypeResolver,
            $fieldName,
            $fieldArgName,
        );
        return $this->consolidatedFieldArgDefaultValueCache[$cacheKey];
    }

    /**
     * Consolidation of the schema field arguments. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     */
    final public function getConsolidatedFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        // Cache the result
        $cacheKey = $objectTypeResolver::class . '.' . $fieldName . '(' . $fieldArgName . ':)';
        if (array_key_exists($cacheKey, $this->consolidatedFieldArgTypeModifiersCache)) {
            return $this->consolidatedFieldArgTypeModifiersCache[$cacheKey];
        }
        $this->consolidatedFieldArgTypeModifiersCache[$cacheKey] = $this->hooksAPI->applyFilters(
            HookNames::OBJECT_TYPE_FIELD_ARG_TYPE_MODIFIERS,
            $this->getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
            $this,
            $objectTypeResolver,
            $fieldName,
            $fieldArgName,
        );
        return $this->consolidatedFieldArgTypeModifiersCache[$cacheKey];
    }

    /**
     * Consolidation of the schema field arguments. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     */
    final public function getFieldArgsSchemaDefinition(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        // Cache the result
        $cacheKey = $objectTypeResolver::class . '.' . $fieldName;
        if (array_key_exists($cacheKey, $this->schemaFieldArgsCache)) {
            return $this->schemaFieldArgsCache[$cacheKey];
        }
        $schemaFieldArgs = [];
        $consolidatedFieldArgNameTypeResolvers = $this->getConsolidatedFieldArgNameTypeResolvers($objectTypeResolver, $fieldName);
        foreach ($consolidatedFieldArgNameTypeResolvers as $fieldArgName => $fieldArgInputTypeResolver) {
            $schemaFieldArgs[$fieldArgName] = $this->getFieldOrDirectiveArgTypeSchemaDefinition(
                $fieldArgName,
                $fieldArgInputTypeResolver,
                $this->getConsolidatedFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName),
                $this->getConsolidatedFieldArgDefaultValue($objectTypeResolver, $fieldName, $fieldArgName),
                $this->getConsolidatedFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
            );
        }
        $this->schemaFieldArgsCache[$cacheKey] = $schemaFieldArgs;
        return $this->schemaFieldArgsCache[$cacheKey];
    }

    public function getFieldDeprecationMessage(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($objectTypeResolver, $fieldName);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getFieldDeprecationMessage($objectTypeResolver, $fieldName);
        }
        return null;
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($objectTypeResolver, $fieldName);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getFieldTypeResolver($objectTypeResolver, $fieldName);
        }
        return $this->schemaDefinitionService->getDefaultConcreteTypeResolver();
    }

    /**
     * Validate the constraints for a field argument
     *
     * @return string[] Error messages
     */
    public function validateFieldArgument(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName,
        string $fieldArgName,
        mixed $fieldArgValue
    ): array {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($objectTypeResolver, $fieldName);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->validateFieldArgument($objectTypeResolver, $fieldName, $fieldArgName, $fieldArgValue);
        }
        return [];
    }

    public function isGlobal(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): bool
    {
        return false;
    }

    /**
     * Define if to use the version to decide if to process the field or not
     */
    public function decideCanProcessBasedOnVersionConstraint(ObjectTypeResolverInterface $objectTypeResolver): bool
    {
        return false;
    }

    /**
     * Indicates if the fieldResolver can process this combination of fieldName and fieldArgs
     * It is required to support a multiverse of fields: different fieldResolvers can resolve the field, based on the required version (passed through $fieldArgs['branch'])
     *
     * @param array<string, mixed> $fieldArgs
     */
    public function resolveCanProcess(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, array $fieldArgs = []): bool
    {
        /** Check if to validate the version */
        if (
            Environment::enableSemanticVersionConstraints() &&
            $this->decideCanProcessBasedOnVersionConstraint($objectTypeResolver)
        ) {
            /**
             * Please notice: we can get the fieldVersion directly from this instance,
             * and not from the schemaDefinition, because the version is set at the ObjectTypeFieldResolver level,
             * and not the InterfaceTypeFieldResolver, which is the other entity filling data
             * inside the schemaDefinition object.
             * If this field is tagged with a version...
             */
            if ($schemaFieldVersion = $this->getFieldVersion($objectTypeResolver, $fieldName)) {
                $vars = ApplicationState::getVars();
                /**
                 * Get versionConstraint in this order:
                 * 1. Passed as field argument
                 * 2. Through param `fieldVersionConstraints[$fieldName]`: specific to the namespaced type + field
                 * 3. Through param `fieldVersionConstraints[$fieldName]`: specific to the type + field
                 * 4. Through param `versionConstraint`: applies to all fields and directives in the query
                 */
                $versionConstraint =
                    $fieldArgs[SchemaDefinition::VERSION_CONSTRAINT]
                    ?? VersioningHelpers::getVersionConstraintsForField(
                        $objectTypeResolver->getNamespacedTypeName(),
                        $fieldName
                    )
                    ?? VersioningHelpers::getVersionConstraintsForField(
                        $objectTypeResolver->getTypeName(),
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
    public function resolveFieldValidationErrorDescriptions(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, array $fieldArgs = []): array
    {
        /**
         * Validate all mandatory args have been provided
         */
        $consolidatedFieldArgNameTypeResolvers = $this->getConsolidatedFieldArgNameTypeResolvers($objectTypeResolver, $fieldName);
        $mandatoryConsolidatedFieldArgNames = array_keys(array_filter(
            $consolidatedFieldArgNameTypeResolvers,
            fn (string $fieldArgName) => ($this->getConsolidatedFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName) & SchemaTypeModifiers::MANDATORY) === SchemaTypeModifiers::MANDATORY,
            ARRAY_FILTER_USE_KEY
        ));
        if (
            $maybeError = $this->validateNotMissingFieldOrDirectiveArguments(
                $mandatoryConsolidatedFieldArgNames,
                $fieldName,
                $fieldArgs,
                ResolverTypes::FIELD
            )
        ) {
            return [$maybeError];
        }

        if ($this->canValidateFieldOrDirectiveArgumentsWithValuesForSchema($fieldArgs)) {
            /**
             * Validate all enum values provided via args are valid
             */
            /** @var array<string, EnumTypeResolverInterface> */
            $enumConsolidatedFieldArgNameTypeResolvers = array_filter(
                $consolidatedFieldArgNameTypeResolvers,
                fn (InputTypeResolverInterface $inputTypeResolver) => $inputTypeResolver instanceof EnumTypeResolverInterface
            );
            $enumConsolidatedFieldArgNamesIsArrayOfArrays = $enumConsolidatedFieldArgNamesIsArray = [];
            foreach (array_keys($enumConsolidatedFieldArgNameTypeResolvers) as $fieldArgName) {
                $consolidatedFieldArgTypeModifiers = $this->getConsolidatedFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName);
                $enumConsolidatedFieldArgNamesIsArrayOfArrays[$fieldArgName] = ($consolidatedFieldArgTypeModifiers & SchemaTypeModifiers::IS_ARRAY_OF_ARRAYS) === SchemaTypeModifiers::IS_ARRAY_OF_ARRAYS;
                $enumConsolidatedFieldArgNamesIsArray[$fieldArgName] = ($consolidatedFieldArgTypeModifiers & SchemaTypeModifiers::IS_ARRAY) === SchemaTypeModifiers::IS_ARRAY;
            }
            [$maybeErrors] = $this->validateEnumFieldOrDirectiveArguments(
                $enumConsolidatedFieldArgNameTypeResolvers,
                $enumConsolidatedFieldArgNamesIsArrayOfArrays,
                $enumConsolidatedFieldArgNamesIsArray,
                $fieldName,
                $fieldArgs,
                ResolverTypes::FIELD
            );
            if ($maybeErrors) {
                return $maybeErrors;
            }

            /**
             * Validate field argument constraints
             */
            if (
                $maybeErrors = $this->resolveFieldArgumentErrors(
                    $objectTypeResolver,
                    $fieldName,
                    $fieldArgs
                )
            ) {
                return $maybeErrors;
            }
        }

        /**
         * If a MutationResolver is declared, let it validate the schema
         */
        $mutationResolver = $this->getFieldMutationResolver($objectTypeResolver, $fieldName);
        if ($mutationResolver !== null && !$this->validateMutationOnObject($objectTypeResolver, $fieldName)) {
            return $mutationResolver->validateErrors($fieldArgs);
        }

        // Custom validations
        return $this->doResolveSchemaValidationErrorDescriptions(
            $objectTypeResolver,
            $fieldName,
            $fieldArgs,
        );
    }

    /**
     * Validate the constraints for the field arguments
     */
    final protected function resolveFieldArgumentErrors(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName,
        array $fieldArgs = []
    ): array {
        $errors = [];
        foreach ($fieldArgs as $fieldArgName => $fieldArgValue) {
            if (
                $maybeErrors = $this->validateFieldArgument(
                    $objectTypeResolver,
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
        return $errors;
    }

    /**
     * Custom validations. Function to override
     */
    protected function doResolveSchemaValidationErrorDescriptions(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName,
        array $fieldArgs = []
    ): array {
        return [];
    }

    public function resolveFieldValidationDeprecationMessages(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, array $fieldArgs = []): array
    {
        $fieldDeprecationMessages = [];

        // Deprecations for the field
        $fieldDeprecationMessage = $this->getConsolidatedFieldDeprecationMessage($objectTypeResolver, $fieldName);
        if ($fieldDeprecationMessage !== null) {
            $fieldDeprecationMessages[] = sprintf(
                $this->translationAPI->__('Field \'%s\' is deprecated: %s', 'component-model'),
                $fieldName,
                $fieldDeprecationMessage
            );
        }

        /**
         * Deprecations for the field args of Enum Type
         */
        $consolidatedFieldArgNameTypeResolvers = $this->getConsolidatedFieldArgNameTypeResolvers($objectTypeResolver, $fieldName);
        /** @var array<string, EnumTypeResolverInterface> */
        $enumConsolidatedFieldArgNameTypeResolvers = array_filter(
            $consolidatedFieldArgNameTypeResolvers,
            fn (InputTypeResolverInterface $inputTypeResolver) => $inputTypeResolver instanceof EnumTypeResolverInterface
        );
        $enumConsolidatedFieldArgNamesIsArrayOfArrays = $enumConsolidatedFieldArgNamesIsArray = [];
        foreach (array_keys($enumConsolidatedFieldArgNameTypeResolvers) as $fieldArgName) {
            $consolidatedFieldArgTypeModifiers = $this->getConsolidatedFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName);
            $enumConsolidatedFieldArgNamesIsArrayOfArrays[$fieldArgName]  = $consolidatedFieldArgTypeModifiers & SchemaTypeModifiers::IS_ARRAY_OF_ARRAYS;
            $enumConsolidatedFieldArgNamesIsArray[$fieldArgName]  = $consolidatedFieldArgTypeModifiers & SchemaTypeModifiers::IS_ARRAY;
        }
        [$maybeErrors, $maybeDeprecations] = $this->validateEnumFieldOrDirectiveArguments(
            $enumConsolidatedFieldArgNameTypeResolvers,
            $enumConsolidatedFieldArgNamesIsArrayOfArrays,
            $enumConsolidatedFieldArgNamesIsArray,
            $fieldName,
            $fieldArgs,
            ResolverTypes::FIELD
        );
        $fieldDeprecationMessages = array_merge(
            $fieldDeprecationMessages,
            $maybeDeprecations
        );

        return $fieldDeprecationMessages;
    }

    /**
     * Fields may not be directly visible in the schema,
     * eg: because they are used only by the application, and must not
     * be exposed to the user (eg: "accessControlLists")
     */
    public function skipExposingFieldInSchema(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): bool
    {
        if (ComponentConfiguration::skipExposingDangerouslyDynamicScalarTypeInSchema()) {
            /**
             * If `DangerouslyDynamic` is disabled, do not expose the field if either:
             *
             *   1. its type is `DangerouslyDynamic`
             *   2. it has any mandatory argument of type `DangerouslyDynamic`
             */
            $consolidatedFieldArgNames = array_keys($this->getConsolidatedFieldArgNameTypeResolvers($objectTypeResolver, $fieldName));
            $consolidatedFieldArgsTypeModifiers = [];
            foreach ($consolidatedFieldArgNames as $fieldArgName) {
                $consolidatedFieldArgsTypeModifiers[$fieldArgName] = $this->getConsolidatedFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName);
            }
            if (
                $this->isDangerouslyDynamicScalarFieldType(
                    $this->getFieldTypeResolver($objectTypeResolver, $fieldName),
                    $this->getConsolidatedFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
                    $consolidatedFieldArgsTypeModifiers
                )
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Field args may not be directly visible in the schema
     */
    public function skipExposingFieldArgInSchema(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): bool
    {
        return false;
    }

    /**
     * Get the "schema" properties as for the fieldName
     */
    final public function getFieldSchemaDefinition(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, array $fieldArgs = []): array
    {
        // First check if the value was cached
        $key = $objectTypeResolver->getNamespacedTypeName() . '|' . $fieldName . '|' . json_encode($fieldArgs);
        if (!isset($this->schemaDefinitionForFieldCache[$key])) {
            $this->schemaDefinitionForFieldCache[$key] = $this->doGetFieldSchemaDefinition($objectTypeResolver, $fieldName, $fieldArgs);
        }
        return $this->schemaDefinitionForFieldCache[$key];
    }

    /**
     * Get the "schema" properties as for the fieldName
     */
    final protected function doGetFieldSchemaDefinition(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, array $fieldArgs = []): array
    {
        $schemaDefinition = $this->getFieldTypeSchemaDefinition(
            $fieldName,
            // This method has no "Consolidated" because it makes no sense
            $this->getFieldTypeResolver($objectTypeResolver, $fieldName),
            $this->getConsolidatedFieldDescription($objectTypeResolver, $fieldName),
            // This method has no "Consolidated" because it makes no sense
            $this->getFieldTypeModifiers($objectTypeResolver, $fieldName),
            $this->getConsolidatedFieldDeprecationMessage($objectTypeResolver, $fieldName),
        );

        if ($args = $this->getFieldArgsSchemaDefinition($objectTypeResolver, $fieldName)) {
            $schemaDefinition[SchemaDefinition::ARGS] = $args;

            // Check it args can be queried without their name
            if ($this->enableOrderedSchemaFieldArgs($objectTypeResolver, $fieldName)) {
                $schemaDefinition[SchemaDefinition::ORDERED_ARGS_ENABLED] = true;
            }
        }

        if (Environment::enableSemanticVersionConstraints()) {
            if ($version = $this->getFieldVersion($objectTypeResolver, $fieldName)) {
                $schemaDefinition[SchemaDefinition::VERSION] = $version;
            }
        }
        if ($this->getFieldMutationResolver($objectTypeResolver, $fieldName) !== null) {
            $schemaDefinition[SchemaDefinition::FIELD_IS_MUTATION] = true;
        }

        if ($extensions = $this->getFieldSchemaDefinitionExtensions($objectTypeResolver, $fieldName, $fieldArgs)) {
            $schemaDefinition[SchemaDefinition::EXTENSIONS] = $extensions;
        }

        return $schemaDefinition;
    }

    public function getFieldSchemaDefinitionExtensions(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, array $fieldArgs = []): array
    {
        return [];
    }

    protected function getInterfaceSchemaDefinitionResolverAdapterClass(): string
    {
        return InterfaceSchemaDefinitionResolverAdapter::class;
    }

    public function enableOrderedSchemaFieldArgs(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): bool
    {
        return true;
    }

    /**
     * Please notice: the version always comes from the fieldResolver, and not from the schemaDefinitionResolver
     * That is because it is the implementer the one who knows what version it is, and not the one defining the interface
     * If the interface changes, the implementer will need to change, so the version will be upgraded
     * But it could also be that the contract doesn't change, but the implementation changes
     * In particular, Interfaces are schemaDefinitionResolver, but they must not indicate the version...
     * it's really not their responsibility
     */
    public function getFieldVersion(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return null;
    }

    protected function hasSchemaFieldVersion(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): bool
    {
        return !empty($this->getFieldVersion($objectTypeResolver, $fieldName));
    }

    public function resolveFieldValidationWarningDescriptions(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, array $fieldArgs = []): array
    {
        $warnings = [];
        if (Environment::enableSemanticVersionConstraints()) {
            /**
             * If restricting the version, and this fieldResolver doesn't have any version, then show a warning
             */
            if ($versionConstraint = $fieldArgs[SchemaDefinition::VERSION_CONSTRAINT] ?? null) {
                /**
                 * If this fieldResolver doesn't have versioning, then it accepts everything
                 */
                if (!$this->decideCanProcessBasedOnVersionConstraint($objectTypeResolver)) {
                    $warnings[] = sprintf(
                        $this->translationAPI->__('The ObjectTypeFieldResolver used to process field with name \'%s\' (which has version \'%s\') does not pay attention to the version constraint; hence, argument \'versionConstraint\', with value \'%s\', was ignored', 'component-model'),
                        $fieldName,
                        $this->getFieldVersion($objectTypeResolver, $fieldName) ?? '',
                        $versionConstraint
                    );
                }
            }
        }
        // If a MutationResolver is declared, let it resolve the value
        $mutationResolver = $this->getFieldMutationResolver($objectTypeResolver, $fieldName);
        if ($mutationResolver !== null) {
            return $mutationResolver->validateWarnings($fieldArgs);
        }
        return $warnings;
    }

    /**
     * @param array<string, mixed> $fieldArgs
     */
    public function resolveCanProcessObject(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs = []
    ): bool {
        return true;
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @return array<array> A set of checkpoint sets
     */
    protected function getValidationCheckpointSets(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs = []
    ): array {
        $validationCheckpointSets = [];
        // Check that mutations can be executed
        if ($this->getFieldMutationResolver($objectTypeResolver, $fieldName) !== null) {
            $validationCheckpointSets[] = CheckpointSets::CAN_EXECUTE_MUTATIONS;
        }
        return $validationCheckpointSets;
    }

    /**
     * @param array<string, mixed> $fieldArgs
     */
    protected function getValidationCheckpointsErrorMessage(
        array $checkpointSet,
        Error $error,
        string $errorMessage,
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs = []
    ): string {
        return $errorMessage;
    }

    /**
     * @param array<string, mixed> $fieldArgs
     */
    public function getValidationErrorDescriptions(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs = []
    ): array {
        // Can perform validation through checkpoints
        if ($checkpointSets = $this->getValidationCheckpointSets($objectTypeResolver, $object, $fieldName, $fieldArgs)) {
            $errorMessages = [];
            foreach ($checkpointSets as $checkpointSet) {
                $validation = $this->engine->validateCheckpoints($checkpointSet);
                if (GeneralUtils::isError($validation)) {
                    /** @var Error */
                    $error = $validation;
                    $errorMessage = $error->getMessageOrCode();
                    // Allow to customize the error message for the failing entity
                    $errorMessages[] = $this->getValidationCheckpointsErrorMessage($checkpointSet, $error, $errorMessage, $objectTypeResolver, $object, $fieldName, $fieldArgs);
                }
            }
            if ($errorMessages) {
                return $errorMessages;
            }
        }

        // If a MutationResolver is declared, let it resolve the value
        $mutationResolver = $this->getFieldMutationResolver($objectTypeResolver, $fieldName);
        if ($mutationResolver !== null && $this->validateMutationOnObject($objectTypeResolver, $fieldName)) {
            // Validate on the object
            $mutationFieldArgs = $this->getFieldArgsToExecuteMutation(
                $fieldArgs,
                $objectTypeResolver,
                $object,
                $fieldName
            );
            return $mutationResolver->validateErrors($mutationFieldArgs);
        }

        return [];
    }

    /**
     * The mutation can be validated either on the schema (`false`)
     * on on the object (`true`)
     */
    public function validateMutationOnObject(
        ObjectTypeResolverInterface $objectTypeResolver,
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
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        // If a MutationResolver is declared, let it resolve the value
        $mutationResolver = $this->getFieldMutationResolver($objectTypeResolver, $fieldName);
        if ($mutationResolver !== null) {
            $mutationFieldArgs = $this->getFieldArgsToExecuteMutation(
                $fieldArgs,
                $objectTypeResolver,
                $object,
                $fieldName
            );
            return $mutationResolver->executeMutation($mutationFieldArgs);
        }
        // Base case: If the fieldName exists as property in the object, then retrieve it
        if (\property_exists($object, $fieldName)) {
            return $object->$fieldName;
        }
        return null;
    }

    protected function getFieldArgsToExecuteMutation(
        array $fieldArgs,
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName
    ): array {
        return $fieldArgs;
    }

    public function getFieldMutationResolver(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName
    ): ?MutationResolverInterface {
        return null;
    }
}
