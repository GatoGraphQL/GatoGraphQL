<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers\ObjectType;

use Exception;
use PoP\ComponentModel\AttachableExtensions\AttachableExtensionManagerInterface;
use PoP\ComponentModel\AttachableExtensions\AttachableExtensionTrait;
use PoP\ComponentModel\Checkpoints\CheckpointInterface;
use PoP\ComponentModel\Checkpoints\EnabledMutationsCheckpoint;
use PoP\ComponentModel\Environment;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FeedbackItemProviders\DeprecationFeedbackItemProvider;
use PoP\ComponentModel\FeedbackItemProviders\ErrorFeedbackItemProvider;
use PoP\ComponentModel\FeedbackItemProviders\WarningFeedbackItemProvider;
use PoP\ComponentModel\FieldResolvers\AbstractFieldResolver;
use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldSchemaDefinitionResolverInterface;
use PoP\ComponentModel\HelperServices\SemverHelperServiceInterface;
use PoP\ComponentModel\Module;
use PoP\ComponentModel\ModuleConfiguration;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\Resolvers\CheckDangerouslyNonSpecificScalarTypeFieldOrDirectiveResolverTrait;
use PoP\ComponentModel\Resolvers\FieldOrDirectiveResolverTrait;
use PoP\ComponentModel\Resolvers\FieldOrDirectiveSchemaDefinitionResolverTrait;
use PoP\ComponentModel\Resolvers\InterfaceSchemaDefinitionResolverAdapter;
use PoP\ComponentModel\Resolvers\WithVersionConstraintFieldOrDirectiveResolverTrait;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaDefinitionServiceInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\DangerouslyNonSpecificScalarTypeScalarTypeResolver;
use PoP\ComponentModel\Versioning\VersioningServiceInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Root\App;
use PoP\Root\Exception\AbstractClientException;
use PoP\Root\Feedback\FeedbackItemResolution;
use SplObjectStorage;

abstract class AbstractObjectTypeFieldResolver extends AbstractFieldResolver implements ObjectTypeFieldResolverInterface
{
    use AttachableExtensionTrait;
    use WithVersionConstraintFieldOrDirectiveResolverTrait;
    use CheckDangerouslyNonSpecificScalarTypeFieldOrDirectiveResolverTrait;
    // Avoid trait collisions for PHP 7.1
    use FieldOrDirectiveResolverTrait, FieldOrDirectiveSchemaDefinitionResolverTrait {
        FieldOrDirectiveSchemaDefinitionResolverTrait::getFieldOrDirectiveArgTypeSchemaDefinition insteadof FieldOrDirectiveResolverTrait;
        FieldOrDirectiveSchemaDefinitionResolverTrait::getTypeSchemaDefinition insteadof FieldOrDirectiveResolverTrait;
        FieldOrDirectiveSchemaDefinitionResolverTrait::processSchemaDefinitionTypeModifiers insteadof FieldOrDirectiveResolverTrait;
        FieldOrDirectiveSchemaDefinitionResolverTrait::getFieldTypeSchemaDefinition insteadof FieldOrDirectiveResolverTrait;
    }

    /**
     * @var array<string,array>
     */
    protected array $schemaDefinitionForFieldCache = [];
    /** @var array<string,string|null> */
    protected array $consolidatedFieldDescriptionCache = [];
    /** @var array<string,array<string,mixed>> */
    protected array $consolidatedFieldExtensionsCache = [];
    /** @var array<string,string|null> */
    protected array $consolidatedFieldDeprecationMessageCache = [];
    /** @var array<string,array<string,InputTypeResolverInterface>> */
    protected array $consolidatedFieldArgNameTypeResolversCache = [];
    /** @var array<string,string[]> */
    protected array $consolidatedAdminFieldArgNamesCache = [];
    /** @var array<string,string|null> */
    protected array $consolidatedFieldArgDescriptionCache = [];
    /** @var array<string,string|null> */
    protected array $consolidatedFieldArgDeprecationMessageCache = [];
    /** @var array<string,mixed> */
    protected array $consolidatedFieldArgDefaultValueCache = [];
    /** @var array<string,int> */
    protected array $consolidatedFieldArgTypeModifiersCache = [];
    /** @var array<string,array<string,mixed>> */
    protected array $schemaFieldArgsCache = [];
    /** @var array<string,array<string,mixed>> */
    protected array $schemaFieldArgExtensionsCache = [];
    /**
     * @var array<string,ObjectTypeFieldSchemaDefinitionResolverInterface>
     */
    protected array $interfaceTypeFieldSchemaDefinitionResolverCache = [];
    /** @var SplObjectStorage<FieldInterface,FieldDataAccessorInterface> */
    protected SplObjectStorage $fieldFieldDataAccessorCache;

    private ?FieldQueryInterpreterInterface $fieldQueryInterpreter = null;
    private ?NameResolverInterface $nameResolver = null;
    private ?SemverHelperServiceInterface $semverHelperService = null;
    private ?SchemaDefinitionServiceInterface $schemaDefinitionService = null;
    private ?AttachableExtensionManagerInterface $attachableExtensionManager = null;
    private ?DangerouslyNonSpecificScalarTypeScalarTypeResolver $dangerouslyNonSpecificScalarTypeScalarTypeResolver = null;
    private ?VersioningServiceInterface $versioningService = null;
    private ?EnabledMutationsCheckpoint $enabledMutationsCheckpoint = null;

    final public function setFieldQueryInterpreter(FieldQueryInterpreterInterface $fieldQueryInterpreter): void
    {
        $this->fieldQueryInterpreter = $fieldQueryInterpreter;
    }
    final protected function getFieldQueryInterpreter(): FieldQueryInterpreterInterface
    {
        return $this->fieldQueryInterpreter ??= $this->instanceManager->getInstance(FieldQueryInterpreterInterface::class);
    }
    final public function setNameResolver(NameResolverInterface $nameResolver): void
    {
        $this->nameResolver = $nameResolver;
    }
    final protected function getNameResolver(): NameResolverInterface
    {
        return $this->nameResolver ??= $this->instanceManager->getInstance(NameResolverInterface::class);
    }
    final public function setSemverHelperService(SemverHelperServiceInterface $semverHelperService): void
    {
        $this->semverHelperService = $semverHelperService;
    }
    final protected function getSemverHelperService(): SemverHelperServiceInterface
    {
        return $this->semverHelperService ??= $this->instanceManager->getInstance(SemverHelperServiceInterface::class);
    }
    final public function setSchemaDefinitionService(SchemaDefinitionServiceInterface $schemaDefinitionService): void
    {
        $this->schemaDefinitionService = $schemaDefinitionService;
    }
    final protected function getSchemaDefinitionService(): SchemaDefinitionServiceInterface
    {
        return $this->schemaDefinitionService ??= $this->instanceManager->getInstance(SchemaDefinitionServiceInterface::class);
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
    final public function setEnabledMutationsCheckpoint(EnabledMutationsCheckpoint $enabledMutationsCheckpoint): void
    {
        $this->enabledMutationsCheckpoint = $enabledMutationsCheckpoint;
    }
    final protected function getEnabledMutationsCheckpoint(): EnabledMutationsCheckpoint
    {
        return $this->enabledMutationsCheckpoint ??= $this->instanceManager->getInstance(EnabledMutationsCheckpoint::class);
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
    public function getConsolidatedFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        // Cache the result
        $cacheKey = $objectTypeResolver::class . '.' . $fieldName;
        if (array_key_exists($cacheKey, $this->consolidatedFieldDescriptionCache)) {
            return $this->consolidatedFieldDescriptionCache[$cacheKey];
        }
        $this->consolidatedFieldDescriptionCache[$cacheKey] = App::applyFilters(
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
    public function getConsolidatedFieldDeprecationMessage(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        // Cache the result
        $cacheKey = $objectTypeResolver::class . '.' . $fieldName;
        if (array_key_exists($cacheKey, $this->consolidatedFieldDeprecationMessageCache)) {
            return $this->consolidatedFieldDeprecationMessageCache[$cacheKey];
        }
        $this->consolidatedFieldDeprecationMessageCache[$cacheKey] = App::applyFilters(
            HookNames::OBJECT_TYPE_FIELD_DEPRECATION_MESSAGE,
            $this->getFieldDeprecationMessage($objectTypeResolver, $fieldName),
            $this,
            $objectTypeResolver,
            $fieldName,
        );
        return $this->consolidatedFieldDeprecationMessageCache[$cacheKey];
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($objectTypeResolver, $fieldName);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName);
        }
        return [];
    }

    /**
     * @return string[]
     */
    public function getAdminFieldArgNames(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($objectTypeResolver, $fieldName);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getAdminFieldArgNames($objectTypeResolver, $fieldName);
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
    public function getConsolidatedFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        // Cache the result
        $cacheKey = $objectTypeResolver::class . '.' . $fieldName;
        if (array_key_exists($cacheKey, $this->consolidatedFieldArgNameTypeResolversCache)) {
            return $this->consolidatedFieldArgNameTypeResolversCache[$cacheKey];
        }

        /**
         * Allow to override/extend the inputs (eg: module "Post Categories" can add
         * input "categories" to field "Root.createPost")
         */
        $consolidatedFieldArgNameTypeResolvers = App::applyFilters(
            HookNames::OBJECT_TYPE_FIELD_ARG_NAME_TYPE_RESOLVERS,
            $this->getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
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
            if (
                Environment::enableSemanticVersionConstraints()
                && $this->hasFieldVersion($objectTypeResolver, $fieldName)
            ) {
                $consolidatedFieldArgNameTypeResolvers[SchemaDefinition::VERSION_CONSTRAINT] = $this->getFieldVersionInputTypeResolver($objectTypeResolver, $fieldName);
            }
        }

        // Exclude the admin field args, if "Admin" Schema is not enabled
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if (!$moduleConfiguration->enableAdminSchema()) {
            $adminFieldArgNames = $this->getConsolidatedAdminFieldArgNames($objectTypeResolver, $fieldName);
            $consolidatedFieldArgNameTypeResolvers = array_filter(
                $consolidatedFieldArgNameTypeResolvers,
                fn (string $fieldArgName) => !in_array($fieldArgName, $adminFieldArgNames),
                ARRAY_FILTER_USE_KEY
            );
        }

        $this->consolidatedFieldArgNameTypeResolversCache[$cacheKey] = $consolidatedFieldArgNameTypeResolvers;
        return $this->consolidatedFieldArgNameTypeResolversCache[$cacheKey];
    }

    /**
     * Consolidation of the schema field arguments. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     */
    public function getConsolidatedAdminFieldArgNames(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        // Cache the result
        $cacheKey = $objectTypeResolver::class . '.' . $fieldName;
        if (array_key_exists($cacheKey, $this->consolidatedAdminFieldArgNamesCache)) {
            return $this->consolidatedAdminFieldArgNamesCache[$cacheKey];
        }
        $this->consolidatedAdminFieldArgNamesCache[$cacheKey] = App::applyFilters(
            HookNames::OBJECT_TYPE_FIELD_ARG_NAME_TYPE_RESOLVERS,
            $this->getAdminFieldArgNames($objectTypeResolver, $fieldName),
            $this,
            $objectTypeResolver,
            $fieldName,
        );
        return $this->consolidatedAdminFieldArgNamesCache[$cacheKey];
    }

    /**
     * Consolidation of the schema field arguments. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     */
    public function getConsolidatedFieldArgDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): ?string
    {
        // Cache the result
        $cacheKey = $objectTypeResolver::class . '.' . $fieldName . '(' . $fieldArgName . ':)';
        if (array_key_exists($cacheKey, $this->consolidatedFieldArgDescriptionCache)) {
            return $this->consolidatedFieldArgDescriptionCache[$cacheKey];
        }
        $this->consolidatedFieldArgDescriptionCache[$cacheKey] = App::applyFilters(
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
    public function getConsolidatedFieldArgDefaultValue(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): mixed
    {
        // Cache the result
        $cacheKey = $objectTypeResolver::class . '.' . $fieldName . '(' . $fieldArgName . ':)';
        if (array_key_exists($cacheKey, $this->consolidatedFieldArgDefaultValueCache)) {
            return $this->consolidatedFieldArgDefaultValueCache[$cacheKey];
        }
        $this->consolidatedFieldArgDefaultValueCache[$cacheKey] = App::applyFilters(
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
    public function getConsolidatedFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        // Cache the result
        $cacheKey = $objectTypeResolver::class . '.' . $fieldName . '(' . $fieldArgName . ':)';
        if (array_key_exists($cacheKey, $this->consolidatedFieldArgTypeModifiersCache)) {
            return $this->consolidatedFieldArgTypeModifiersCache[$cacheKey];
        }
        $this->consolidatedFieldArgTypeModifiersCache[$cacheKey] = App::applyFilters(
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
            $fieldArgDescription =
                $this->getConsolidatedFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName)
                ?? $fieldArgInputTypeResolver->getTypeDescription();
            $schemaFieldArgs[$fieldArgName] = $this->getFieldOrDirectiveArgTypeSchemaDefinition(
                $fieldArgName,
                $fieldArgInputTypeResolver,
                $fieldArgDescription,
                $this->getConsolidatedFieldArgDefaultValue($objectTypeResolver, $fieldName, $fieldArgName),
                $this->getConsolidatedFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
            );
            $schemaFieldArgs[$fieldArgName][SchemaDefinition::EXTENSIONS] = $this->getConsolidatedFieldArgExtensionsSchemaDefinition($objectTypeResolver, $fieldName, $fieldArgName);
        }
        $this->schemaFieldArgsCache[$cacheKey] = $schemaFieldArgs;
        return $this->schemaFieldArgsCache[$cacheKey];
    }

    protected function getFieldArgExtensionsSchemaDefinition(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): array
    {
        $adminFieldArgNames = $this->getConsolidatedAdminFieldArgNames($objectTypeResolver, $fieldName);
        return [
            SchemaDefinition::IS_ADMIN_ELEMENT => in_array($fieldArgName, $adminFieldArgNames),
        ];
    }

    /**
     * Consolidation of the schema field arguments. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     */
    final protected function getConsolidatedFieldArgExtensionsSchemaDefinition(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): array
    {
        // Cache the result
        $cacheKey = $objectTypeResolver::class . '.' . $fieldName . '(' . $fieldArgName . ':)';
        if (array_key_exists($cacheKey, $this->schemaFieldArgExtensionsCache)) {
            return $this->schemaFieldArgExtensionsCache[$cacheKey];
        }
        $this->schemaFieldArgExtensionsCache[$cacheKey] = App::applyFilters(
            HookNames::OBJECT_TYPE_FIELD_ARG_EXTENSIONS,
            $this->getFieldArgExtensionsSchemaDefinition($objectTypeResolver, $fieldName, $fieldArgName),
            $this,
            $objectTypeResolver,
            $fieldName,
            $fieldArgName,
        );
        return $this->schemaFieldArgExtensionsCache[$cacheKey];
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
        return $this->getSchemaDefinitionService()->getDefaultConcreteTypeResolver();
    }

    /**
     * Validate the constraints for a field argument
     *
     * @return FeedbackItemResolution[] Errors
     */
    public function validateFieldArgValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName,
        string $fieldArgName,
        mixed $fieldArgValue
    ): array {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($objectTypeResolver, $fieldName);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->validateFieldArgValue($objectTypeResolver, $fieldName, $fieldArgName, $fieldArgValue);
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
     * It is required to support a multiverse of fields: different fieldResolvers can resolve the field, based on the required version (passed through $fieldDataAccessor->getValue('branch'))
     */
    public function resolveCanProcess(
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldInterface $field,
    ): bool {
        /** Check if to validate the version */
        if (
            Environment::enableSemanticVersionConstraints()
            && $this->decideCanProcessBasedOnVersionConstraint($objectTypeResolver)
            && $this->hasFieldVersion($objectTypeResolver, $field->getName())
        ) {
            /**
             * Please notice: we can get the fieldVersion directly from this instance,
             * and not from the schemaDefinition, because the version is set at the ObjectTypeFieldResolver level,
             * and not the InterfaceTypeFieldResolver, which is the other entity filling data
             * inside the schemaDefinition object.
             * If this field is tagged with a version...
             */
            $schemaFieldVersion = $this->getFieldVersion($objectTypeResolver, $field->getName());
            /**
             * Get versionConstraint in this order:
             * 1. Passed as field argument
             * 2. Through param `fieldVersionConstraints[$field->getName()]`: specific to the namespaced type + field
             * 3. Through param `fieldVersionConstraints[$field->getName()]`: specific to the type + field
             * 4. Through param `versionConstraint`: applies to all fields and directives in the query
             */
            $versionConstraint =
                $field->getArgument(SchemaDefinition::VERSION_CONSTRAINT)?->getValue()
                ?? $this->getVersioningService()->getVersionConstraintsForField(
                    $objectTypeResolver->getNamespacedTypeName(),
                    $field->getName()
                )
                ?? $this->getVersioningService()->getVersionConstraintsForField(
                    $objectTypeResolver->getTypeName(),
                    $field->getName()
                )
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
            return $this->getSemverHelperService()->satisfies($schemaFieldVersion, $versionConstraint);
        }
        return true;
    }

    public function validateResolvedFieldType(
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldInterface $field,
    ): bool {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return $moduleConfiguration->validateFieldTypeResponseWithSchemaDefinition();
    }

    /**
     * Custom validations
     *
     * @return FeedbackItemResolution[] Errors
     */
    public function validateFieldKeyValues(
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldDataAccessorInterface $fieldDataAccessor,
    ): array {
        return [];
    }

    public function collectFieldValidationDeprecationMessages(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName,
        array $fieldArgs,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $fieldDeprecationMessage = $this->getConsolidatedFieldDeprecationMessage($objectTypeResolver, $fieldName);
        if ($fieldDeprecationMessage !== null) {
            $objectTypeFieldResolutionFeedbackStore->addDeprecation(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        DeprecationFeedbackItemProvider::class,
                        DeprecationFeedbackItemProvider::D1,
                        [
                            $fieldName,
                            $fieldDeprecationMessage,
                        ]
                    ),
                    LocationHelper::getNonSpecificLocation(),
                    $objectTypeResolver,
                )
            );
        }
    }

    /**
     * Fields may not be directly visible in the schema,
     * eg: because they are used only by the application, and must not
     * be exposed to the user (eg: "accessControlLists")
     */
    public function skipExposingFieldInSchema(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): bool
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if ($moduleConfiguration->skipExposingDangerouslyNonSpecificScalarTypeTypeInSchema()) {
            /**
             * If `DangerouslyNonSpecificScalar` is disabled, do not expose the field if either:
             *
             *   1. its type is `DangerouslyNonSpecificScalar`
             *   2. it has any mandatory argument of type `DangerouslyNonSpecificScalar`
             */
            $consolidatedFieldArgNames = array_keys($this->getConsolidatedFieldArgNameTypeResolvers($objectTypeResolver, $fieldName));
            $consolidatedFieldArgsTypeModifiers = [];
            foreach ($consolidatedFieldArgNames as $fieldArgName) {
                $consolidatedFieldArgsTypeModifiers[$fieldArgName] = $this->getConsolidatedFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName);
            }
            if (
                $this->isDangerouslyNonSpecificScalarTypeFieldType(
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
    final public function getFieldSchemaDefinition(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        // First check if the value was cached
        $key = $objectTypeResolver->getNamespacedTypeName() . '|' . $fieldName;
        if (!isset($this->schemaDefinitionForFieldCache[$key])) {
            $this->schemaDefinitionForFieldCache[$key] = $this->doGetFieldSchemaDefinition($objectTypeResolver, $fieldName);
        }
        return $this->schemaDefinitionForFieldCache[$key];
    }

    /**
     * Get the "schema" properties as for the fieldName
     */
    final protected function doGetFieldSchemaDefinition(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        $fieldTypeResolver = $this->getFieldTypeResolver($objectTypeResolver, $fieldName);
        $fieldDescription =
            $this->getConsolidatedFieldDescription($objectTypeResolver, $fieldName)
            ?? $fieldTypeResolver->getTypeDescription();
        $schemaDefinition = $this->getFieldTypeSchemaDefinition(
            $fieldName,
            // This method has no "Consolidated" because it makes no sense
            $fieldTypeResolver,
            $fieldDescription,
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

        if (Environment::enableSemanticVersionConstraints() && $this->hasFieldVersion($objectTypeResolver, $fieldName)) {
            $schemaDefinition[SchemaDefinition::VERSION] = $this->getFieldVersion($objectTypeResolver, $fieldName);
        }

        $schemaDefinition[SchemaDefinition::EXTENSIONS] = $this->getConsolidatedFieldExtensionsSchemaDefinition($objectTypeResolver, $fieldName);

        return $schemaDefinition;
    }

    /**
     * Watch out: The same extensions must be present for both
     * the ObjectType and the InterfaceType!
     *
     * @return array<string,mixed>
     */
    protected function getFieldExtensionsSchemaDefinition(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return [
            SchemaDefinition::FIELD_IS_MUTATION => $this->getFieldMutationResolver($objectTypeResolver, $fieldName) !== null,
            SchemaDefinition::IS_ADMIN_ELEMENT => in_array($fieldName, $this->getAdminFieldNames()),
        ];
    }

    /**
     * Consolidation of the schema field arguments. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     */
    final protected function getConsolidatedFieldExtensionsSchemaDefinition(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        // Cache the result
        $cacheKey = $objectTypeResolver::class . '.' . $fieldName;
        if (array_key_exists($cacheKey, $this->consolidatedFieldExtensionsCache)) {
            return $this->consolidatedFieldExtensionsCache[$cacheKey];
        }
        $this->consolidatedFieldExtensionsCache[$cacheKey] = App::applyFilters(
            HookNames::OBJECT_TYPE_FIELD_EXTENSIONS,
            $this->getFieldExtensionsSchemaDefinition($objectTypeResolver, $fieldName),
            $this,
            $objectTypeResolver,
            $fieldName,
        );
        return $this->consolidatedFieldExtensionsCache[$cacheKey];
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

    final public function hasFieldVersion(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): bool
    {
        return !empty($this->getFieldVersion($objectTypeResolver, $fieldName))
            && $this->getFieldVersionInputTypeResolver($objectTypeResolver, $fieldName) !== null;
    }

    public function getFieldVersionInputTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?InputTypeResolverInterface
    {
        return null;
    }

    /**
     * @return FeedbackItemResolution[]
     */
    public function resolveFieldValidationWarnings(
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldDataAccessorInterface $fieldDataAccessor
    ): array {
        $warningFeedbackItemResolutions = [];
        if (Environment::enableSemanticVersionConstraints()) {
            /**
             * If restricting the version, and this fieldResolver doesn't have any version, then show a warning
             */
            if ($versionConstraint = $fieldDataAccessor->getValue(SchemaDefinition::VERSION_CONSTRAINT)) {
                /**
                 * If this fieldResolver doesn't have versioning, then it accepts everything
                 */
                if (!$this->decideCanProcessBasedOnVersionConstraint($objectTypeResolver)) {
                    $warningFeedbackItemResolutions[] = new FeedbackItemResolution(
                        WarningFeedbackItemProvider::class,
                        WarningFeedbackItemProvider::W2,
                        [
                            $fieldDataAccessor->getFieldName(),
                            $this->getFieldVersion($objectTypeResolver, $fieldDataAccessor->getFieldName()) ?? '',
                            $versionConstraint
                        ]
                    );
                }
            }
        }
        // If a MutationResolver is declared, let it resolve the value
        $mutationResolver = $this->getFieldMutationResolver($objectTypeResolver, $fieldDataAccessor->getFieldName());
        if ($mutationResolver !== null) {
            $warningFeedbackItemResolutions = array_merge(
                $warningFeedbackItemResolutions,
                $mutationResolver->validateWarnings($fieldDataAccessor)
            );
        }
        return $warningFeedbackItemResolutions;
    }

    /**
     * @param array<string,mixed> $fieldArgs
     * @return CheckpointInterface[]
     */
    public function getValidationCheckpoints(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs
    ): array {
        $validationCheckpoints = [];
        // Check that mutations can be executed
        if ($this->getFieldMutationResolver($objectTypeResolver, $fieldName) !== null) {
            $validationCheckpoints[] = $this->getEnabledMutationsCheckpoint();
        }
        return $validationCheckpoints;
    }

    public function validateFieldDataForObject(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
    }

    /**
     * Indicate: if the field has a single field argument, which is of type InputObject,
     * then retrieve the value for its input fields?
     *
     * By default, that's the case with mutations, as they pass a single input
     * under name "input".
     */
    public function extractInputObjectFieldForMutation(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName,
    ): bool {
        $mutationResolver = $this->getFieldMutationResolver($objectTypeResolver, $fieldName);
        return $mutationResolver !== null;
    }

    /**
     * If the field has a single argument, which is of type InputObject,
     * then retrieve the value for its input fields.
     */
    public function getInputObjectUnderFieldArgumentName(
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldInterface $field,
    ): ?string {
        $fieldArgNameTypeResolvers = $this->getFieldArgNameTypeResolvers($objectTypeResolver, $field->getName());

        // Check if there is only one fieldArg
        if (count($fieldArgNameTypeResolvers) !== 1) {
            return null;
        }

        // Check if the fieldArg is an InputObject
        $fieldArgName = key($fieldArgNameTypeResolvers);
        $fieldArgTypeResolver = $fieldArgNameTypeResolvers[$fieldArgName];
        if (!($fieldArgTypeResolver instanceof InputObjectTypeResolverInterface)) {
            return null;
        }

        return $fieldArgName;
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

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        // If a MutationResolver is declared, let it resolve the value
        $mutationResolver = $this->getFieldMutationResolver($objectTypeResolver, $fieldDataAccessor->getFieldName());
        if ($mutationResolver !== null) {
            return $this->executeMutation(
                $objectTypeResolver,
                $object,
                $fieldDataAccessor,
                $objectTypeFieldResolutionFeedbackStore,
            );
        }
        // Base case: If the field->getName() exists as property in the object, then retrieve it
        if (\property_exists($object, $fieldDataAccessor->getFieldName())) {
            return $object->{$fieldDataAccessor->getFieldName()};
        }
        return null;
    }

    protected function executeMutation(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        // If a MutationResolver is declared, let it resolve the value
        $mutationResolver = $this->getFieldMutationResolver($objectTypeResolver, $fieldDataAccessor->getFieldName());
        try {
            return $mutationResolver->executeMutation($fieldDataAccessor);
        } catch (Exception $e) {
            /** @var ModuleConfiguration */
            $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
            if ($moduleConfiguration->logExceptionErrorMessagesAndTraces()) {
                $objectTypeFieldResolutionFeedbackStore->addLog(
                    new ObjectTypeFieldResolutionFeedback(
                        new FeedbackItemResolution(
                            ErrorFeedbackItemProvider::class,
                            ErrorFeedbackItemProvider::E6A,
                            [
                                $fieldDataAccessor->getFieldName(),
                                $e->getMessage(),
                                $e->getTraceAsString(),
                            ]
                        ),
                        LocationHelper::getNonSpecificLocation(),
                        $objectTypeResolver,
                    )
                );
            }
            $sendExceptionToClient = $e instanceof AbstractClientException
                || $moduleConfiguration->sendExceptionErrorMessages();
            $feedbackItemResolution = $sendExceptionToClient
                ? ($moduleConfiguration->sendExceptionTraces()
                    ? new FeedbackItemResolution(
                        ErrorFeedbackItemProvider::class,
                        ErrorFeedbackItemProvider::E6A,
                        [
                            $fieldDataAccessor->getFieldName(),
                            $e->getMessage(),
                            $e->getTraceAsString(),
                        ]
                    )
                    : new FeedbackItemResolution(
                        ErrorFeedbackItemProvider::class,
                        ErrorFeedbackItemProvider::E6,
                        [
                            $fieldDataAccessor->getFieldName(),
                            $e->getMessage(),
                        ]
                    )
                )
                : new FeedbackItemResolution(
                    ErrorFeedbackItemProvider::class,
                    ErrorFeedbackItemProvider::E7,
                    [
                        $fieldDataAccessor->getFieldName(),
                    ]
                );
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    $feedbackItemResolution,
                    LocationHelper::getNonSpecificLocation(),
                    $objectTypeResolver,
                )
            );
            return null;
        }
    }

    /**
     * @param array<string,mixed> $fieldDataForObject
     */
    public function prepareFieldDataForObject(
        array &$fieldDataForObject,
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldInterface $field,
        object $object,
    ): void {
    }

    public function getFieldMutationResolver(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName
    ): ?MutationResolverInterface {
        return null;
    }

    /**
     * Apply customizations to the field data
     *
     * @param array<string,mixed> $fieldData
     * @return array<string,mixed>|null null in case of validation error
     */
    public function prepareFieldData(
        array $fieldData,
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldInterface $field,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): ?array {
        return $fieldData;
    }
}
