<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers\ObjectType;

use Exception;
use PoP\ComponentModel\AttachableExtensions\AttachableExtensionManagerInterface;
use PoP\ComponentModel\AttachableExtensions\AttachableExtensionTrait;
use PoP\ComponentModel\CheckpointSets\CheckpointSets;
use PoP\ComponentModel\Component;
use PoP\ComponentModel\ComponentConfiguration;
use PoP\ComponentModel\Engine\EngineInterface;
use PoP\ComponentModel\Environment;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FeedbackItemProviders\FeedbackItemProvider;
use PoP\ComponentModel\FieldResolvers\AbstractFieldResolver;
use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldSchemaDefinitionResolverInterface;
use PoP\ComponentModel\HelperServices\SemverHelperServiceInterface;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\Resolvers\CheckDangerouslyDynamicScalarFieldOrDirectiveResolverTrait;
use PoP\ComponentModel\Resolvers\FieldOrDirectiveResolverTrait;
use PoP\ComponentModel\Resolvers\FieldOrDirectiveSchemaDefinitionResolverTrait;
use PoP\ComponentModel\Resolvers\InterfaceSchemaDefinitionResolverAdapter;
use PoP\ComponentModel\Resolvers\ResolverTypes;
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
use PoP\ComponentModel\TypeResolvers\ScalarType\DangerouslyDynamicScalarTypeResolver;
use PoP\ComponentModel\Versioning\VersioningServiceInterface;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Root\App;
use PoP\Root\Exception\AbstractClientException;

abstract class AbstractObjectTypeFieldResolver extends AbstractFieldResolver implements ObjectTypeFieldResolverInterface
{
    use AttachableExtensionTrait;
    use WithVersionConstraintFieldOrDirectiveResolverTrait;
    use CheckDangerouslyDynamicScalarFieldOrDirectiveResolverTrait;
    // Avoid trait collisions for PHP 7.1
    use FieldOrDirectiveResolverTrait, FieldOrDirectiveSchemaDefinitionResolverTrait {
        FieldOrDirectiveSchemaDefinitionResolverTrait::getFieldOrDirectiveArgTypeSchemaDefinition insteadof FieldOrDirectiveResolverTrait;
        FieldOrDirectiveSchemaDefinitionResolverTrait::getTypeSchemaDefinition insteadof FieldOrDirectiveResolverTrait;
        FieldOrDirectiveSchemaDefinitionResolverTrait::processSchemaDefinitionTypeModifiers insteadof FieldOrDirectiveResolverTrait;
        FieldOrDirectiveSchemaDefinitionResolverTrait::getFieldTypeSchemaDefinition insteadof FieldOrDirectiveResolverTrait;
    }

    /**
     * @var array<string, array>
     */
    protected array $schemaDefinitionForFieldCache = [];
    /** @var array<string, string|null> */
    protected array $consolidatedFieldDescriptionCache = [];
    /** @var array<string, array<string, mixed>> */
    protected array $consolidatedFieldExtensionsCache = [];
    /** @var array<string, string|null> */
    protected array $consolidatedFieldDeprecationMessageCache = [];
    /** @var array<string, array<string, InputTypeResolverInterface>> */
    protected array $consolidatedFieldArgNameTypeResolversCache = [];
    /** @var array<string, string[]> */
    protected array $consolidatedAdminFieldArgNamesCache = [];
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
    /** @var array<string, array<string, mixed>> */
    protected array $schemaFieldArgExtensionsCache = [];
    /**
     * @var array<string, ObjectTypeFieldSchemaDefinitionResolverInterface>
     */
    protected array $interfaceTypeFieldSchemaDefinitionResolverCache = [];

    private ?FieldQueryInterpreterInterface $fieldQueryInterpreter = null;
    private ?NameResolverInterface $nameResolver = null;
    private ?SemverHelperServiceInterface $semverHelperService = null;
    private ?SchemaDefinitionServiceInterface $schemaDefinitionService = null;
    private ?EngineInterface $engine = null;
    private ?AttachableExtensionManagerInterface $attachableExtensionManager = null;
    private ?DangerouslyDynamicScalarTypeResolver $dangerouslyDynamicScalarTypeResolver = null;
    private ?VersioningServiceInterface $versioningService = null;

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
    final public function setEngine(EngineInterface $engine): void
    {
        $this->engine = $engine;
    }
    final protected function getEngine(): EngineInterface
    {
        return $this->engine ??= $this->instanceManager->getInstance(EngineInterface::class);
    }
    final public function setAttachableExtensionManager(AttachableExtensionManagerInterface $attachableExtensionManager): void
    {
        $this->attachableExtensionManager = $attachableExtensionManager;
    }
    final protected function getAttachableExtensionManager(): AttachableExtensionManagerInterface
    {
        return $this->attachableExtensionManager ??= $this->instanceManager->getInstance(AttachableExtensionManagerInterface::class);
    }
    final public function setDangerouslyDynamicScalarTypeResolver(DangerouslyDynamicScalarTypeResolver $dangerouslyDynamicScalarTypeResolver): void
    {
        $this->dangerouslyDynamicScalarTypeResolver = $dangerouslyDynamicScalarTypeResolver;
    }
    final protected function getDangerouslyDynamicScalarTypeResolver(): DangerouslyDynamicScalarTypeResolver
    {
        return $this->dangerouslyDynamicScalarTypeResolver ??= $this->instanceManager->getInstance(DangerouslyDynamicScalarTypeResolver::class);
    }
    final public function setVersioningService(VersioningServiceInterface $versioningService): void
    {
        $this->versioningService = $versioningService;
    }
    final protected function getVersioningService(): VersioningServiceInterface
    {
        return $this->versioningService ??= $this->instanceManager->getInstance(VersioningServiceInterface::class);
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
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        if (!$componentConfiguration->enableAdminSchema()) {
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
     * @return string[] Error messages
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
     * It is required to support a multiverse of fields: different fieldResolvers can resolve the field, based on the required version (passed through $fieldArgs['branch'])
     *
     * @param array<string, mixed> $fieldArgs
     */
    public function resolveCanProcess(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, array $fieldArgs): bool
    {
        /** Check if to validate the version */
        if (
            Environment::enableSemanticVersionConstraints()
            && $this->decideCanProcessBasedOnVersionConstraint($objectTypeResolver)
            && $this->hasFieldVersion($objectTypeResolver, $fieldName)
        ) {
            /**
             * Please notice: we can get the fieldVersion directly from this instance,
             * and not from the schemaDefinition, because the version is set at the ObjectTypeFieldResolver level,
             * and not the InterfaceTypeFieldResolver, which is the other entity filling data
             * inside the schemaDefinition object.
             * If this field is tagged with a version...
             */
            $schemaFieldVersion = $this->getFieldVersion($objectTypeResolver, $fieldName);
            /**
             * Get versionConstraint in this order:
             * 1. Passed as field argument
             * 2. Through param `fieldVersionConstraints[$fieldName]`: specific to the namespaced type + field
             * 3. Through param `fieldVersionConstraints[$fieldName]`: specific to the type + field
             * 4. Through param `versionConstraint`: applies to all fields and directives in the query
             */
            $versionConstraint =
                $fieldArgs[SchemaDefinition::VERSION_CONSTRAINT]
                ?? $this->getVersioningService()->getVersionConstraintsForField(
                    $objectTypeResolver->getNamespacedTypeName(),
                    $fieldName
                )
                ?? $this->getVersioningService()->getVersionConstraintsForField(
                    $objectTypeResolver->getTypeName(),
                    $fieldName
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
    public function resolveFieldValidationErrorDescriptions(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, array $fieldArgs): array
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
            $mutationFieldArgs = $this->getConsolidatedMutationFieldArgs($objectTypeResolver, $fieldName, $fieldArgs);
            return $mutationResolver->validateErrors($mutationFieldArgs);
        }

        // Custom validations
        return $this->doResolveSchemaValidationErrorDescriptions(
            $objectTypeResolver,
            $fieldName,
            $fieldArgs,
        );
    }

    public function validateResolvedFieldType(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName,
        array $fieldArgs,
    ): bool {
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        return $componentConfiguration->validateFieldTypeResponseWithSchemaDefinition();
    }

    /**
     * Validate the constraints for the field arguments
     */
    final protected function resolveFieldArgumentErrors(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName,
        array $fieldArgs
    ): array {
        $errors = [];
        $fieldArgNameTypeResolvers = $this->getConsolidatedFieldArgNameTypeResolvers($objectTypeResolver, $fieldName);
        foreach ($fieldArgs as $fieldArgName => $fieldArgValue) {
            /**
             * If the field is an InputObject, let it perform validations on its input fields
             */
            $fieldArgTypeResolver = $fieldArgNameTypeResolvers[$fieldArgName];
            if (
                $fieldArgTypeResolver instanceof InputObjectTypeResolverInterface
            ) {
                $errors = array_merge(
                    $errors,
                    $fieldArgTypeResolver->validateInputValue($fieldArgValue)
                );
            }
            $errors = array_merge(
                $errors,
                $this->validateFieldArgValue(
                    $objectTypeResolver,
                    $fieldName,
                    $fieldArgName,
                    $fieldArgValue
                )
            );
        }
        return $errors;
    }

    /**
     * Custom validations. Function to override
     */
    protected function doResolveSchemaValidationErrorDescriptions(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName,
        array $fieldArgs
    ): array {
        return [];
    }

    public function resolveFieldValidationDeprecationMessages(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, array $fieldArgs): array
    {
        $fieldDeprecationMessages = [];

        // Deprecations for the field
        $fieldDeprecationMessage = $this->getConsolidatedFieldDeprecationMessage($objectTypeResolver, $fieldName);
        if ($fieldDeprecationMessage !== null) {
            $fieldDeprecationMessages[] = sprintf(
                $this->__('Field \'%s\' is deprecated: %s', 'component-model'),
                $fieldName,
                $fieldDeprecationMessage
            );
        }

        return $fieldDeprecationMessages;
    }

    /**
     * Fields may not be directly visible in the schema,
     * eg: because they are used only by the application, and must not
     * be exposed to the user (eg: "accessControlLists")
     */
    public function skipExposingFieldInSchema(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): bool
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        if ($componentConfiguration->skipExposingDangerouslyDynamicScalarTypeInSchema()) {
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
    final public function getFieldSchemaDefinition(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, array $fieldArgs): array
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
    final protected function doGetFieldSchemaDefinition(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, array $fieldArgs): array
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
     * @return array<string, mixed>
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

    public function resolveFieldValidationWarningDescriptions(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, array $fieldArgs): array
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
                        $this->__('The ObjectTypeFieldResolver used to process field with name \'%s\' (which has version \'%s\') does not pay attention to the version constraint; hence, argument \'versionConstraint\', with value \'%s\', was ignored', 'component-model'),
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
            $mutationFieldArgs = $this->getConsolidatedMutationFieldArgs($objectTypeResolver, $fieldName, $fieldArgs);
            $warnings = array_merge(
                $warnings,
                $mutationResolver->validateWarnings($mutationFieldArgs)
            );
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
        array $fieldArgs
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
        array $fieldArgs
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
        string $errorMessage,
        array $checkpointSet,
        FeedbackItemResolution $feedbackItemResolution,
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs
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
        array $fieldArgs
    ): array {
        // Can perform validation through checkpoints
        if ($checkpointSets = $this->getValidationCheckpointSets($objectTypeResolver, $object, $fieldName, $fieldArgs)) {
            $errorMessages = [];
            foreach ($checkpointSets as $checkpointSet) {
                $feedbackItemResolution = $this->getEngine()->validateCheckpoints($checkpointSet);
                if ($feedbackItemResolution !== null) {
                    $errorMessage = $feedbackItemResolution->getMessage();
                    // Allow to customize the error message for the failing entity
                    $errorMessages[] = $this->getValidationCheckpointsErrorMessage(
                        $errorMessage,
                        $checkpointSet,
                        $feedbackItemResolution,
                        $objectTypeResolver,
                        $object,
                        $fieldName,
                        $fieldArgs
                    );
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
            $mutationFieldArgs = $this->getConsolidatedMutationFieldArgsForObject(
                $this->getConsolidatedMutationFieldArgs($objectTypeResolver, $fieldName, $fieldArgs),
                $objectTypeResolver,
                $object,
                $fieldName
            );
            return $mutationResolver->validateErrors($mutationFieldArgs);
        }

        return [];
    }

    /**
     * Retrieve the field arguments to pass to the MutationResolver,
     * for instance from under an InputObject.
     *
     * @param array<string, mixed> $fieldArgs
     * @return array<string, mixed>
     */
    protected function getMutationFieldArgs(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName,
        array $fieldArgs,
    ): array {
        if ($this->extractInputObjectFieldArgsForMutation($objectTypeResolver, $fieldName)) {
            $fieldArgs = $this->maybeGetInputObjectFieldArgs(
                $objectTypeResolver,
                $fieldName,
                $fieldArgs,
            );
        }
        return $fieldArgs;
    }

    /**
     * Consolidation of the schema field arguments. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     */
    final protected function getConsolidatedMutationFieldArgs(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName,
        array $fieldArgs,
    ): array {
        return App::applyFilters(
            HookNames::OBJECT_TYPE_MUTATION_FIELD_ARGS,
            $this->getMutationFieldArgs($objectTypeResolver, $fieldName, $fieldArgs),
            $this,
            $objectTypeResolver,
            $fieldName,
            $fieldArgs,
        );
    }

    /**
     * Indicate: if the field has a single field argument, which is of type InputObject,
     * then retrieve the value for its input fields?
     */
    protected function extractInputObjectFieldArgsForMutation(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName,
    ): bool {
        return true;
    }

    /**
     * If the field has a single argument, which is of type InputObject,
     * then retrieve the value for its input fields.
     *
     * @param array<string, mixed> $fieldArgs
     * @return array<string, mixed>
     */
    private function maybeGetInputObjectFieldArgs(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName,
        array $fieldArgs,
    ): array {
        $fieldArgNameTypeResolvers = $this->getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName);

        // Check if there is only one fieldArg
        if (count($fieldArgNameTypeResolvers) !== 1) {
            return $fieldArgs;
        }

        // Check if the fieldArg is an InputObject
        $fieldArgName = key($fieldArgNameTypeResolvers);
        $fieldArgTypeResolver = $fieldArgNameTypeResolvers[$fieldArgName];
        if (!($fieldArgTypeResolver instanceof InputObjectTypeResolverInterface)) {
            return $fieldArgs;
        }

        // Retrieve the elements under the InputObject, cast to array
        return (array) $fieldArgs[$fieldArgName];
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
     * @param array<string, mixed> $variables
     * @param array<string, mixed> $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs,
        array $variables,
        array $expressions,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
        array $options = []
    ): mixed {
        // If a MutationResolver is declared, let it resolve the value
        $mutationResolver = $this->getFieldMutationResolver($objectTypeResolver, $fieldName);
        if ($mutationResolver !== null) {
            $mutationFieldArgs = $this->getConsolidatedMutationFieldArgsForObject(
                $this->getConsolidatedMutationFieldArgs($objectTypeResolver, $fieldName, $fieldArgs),
                $objectTypeResolver,
                $object,
                $fieldName
            );
            try {
                return $mutationResolver->executeMutation($mutationFieldArgs);
            } catch (Exception $e) {
                /** @var ComponentConfiguration */
                $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
                if ($componentConfiguration->logExceptionErrorMessages()) {
                    // @todo: Implement for Log
                }
                $sendExceptionToClient = $e instanceof AbstractClientException
                    || $componentConfiguration->sendExceptionErrorMessages();
                $feedbackItemResolution = $sendExceptionToClient
                    ? new FeedbackItemResolution(
                        FeedbackItemProvider::class,
                        FeedbackItemProvider::E6,
                        [
                            $fieldName,
                            $e->getMessage()
                        ]
                    )
                    : new FeedbackItemResolution(
                        FeedbackItemProvider::class,
                        FeedbackItemProvider::E7,
                        [
                            $fieldName
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
        // Base case: If the fieldName exists as property in the object, then retrieve it
        if (\property_exists($object, $fieldName)) {
            return $object->$fieldName;
        }
        return null;
    }

    /**
     * Consolidation of the schema field arguments. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     */
    final protected function getConsolidatedMutationFieldArgsForObject(
        array $mutationFieldArgs,
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
    ): array {
        return App::applyFilters(
            HookNames::OBJECT_TYPE_MUTATION_FIELD_ARGS_FOR_OBJECT,
            $this->getMutationFieldArgsForObject(
                $mutationFieldArgs,
                $objectTypeResolver,
                $object,
                $fieldName,
            ),
            $this,
            $mutationFieldArgs,
            $objectTypeResolver,
            $object,
            $fieldName,
        );
    }

    protected function getMutationFieldArgsForObject(
        array $mutationFieldArgs,
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
    ): array {
        return $mutationFieldArgs;
    }

    public function getFieldMutationResolver(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName
    ): ?MutationResolverInterface {
        return null;
    }
}
