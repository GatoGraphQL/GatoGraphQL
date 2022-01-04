<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers\InterfaceType;

use PoP\ComponentModel\AttachableExtensions\AttachableExtensionManagerInterface;
use PoP\ComponentModel\AttachableExtensions\AttachableExtensionTrait;
use PoP\ComponentModel\Component;
use PoP\ComponentModel\ComponentConfiguration;
use PoP\ComponentModel\FieldResolvers\AbstractFieldResolver;
use PoP\ComponentModel\FieldResolvers\InterfaceType\HookNames;
use PoP\ComponentModel\Registries\TypeRegistryInterface;
use PoP\ComponentModel\Resolvers\CheckDangerouslyDynamicScalarFieldOrDirectiveResolverTrait;
use PoP\ComponentModel\Resolvers\FieldOrDirectiveSchemaDefinitionResolverTrait;
use PoP\ComponentModel\Resolvers\WithVersionConstraintFieldOrDirectiveResolverTrait;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaDefinitionServiceInterface;
use PoP\ComponentModel\Schema\SchemaNamespacingServiceInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\DangerouslyDynamicScalarTypeResolver;
use PoP\Engine\CMS\CMSServiceInterface;
use PoP\LooseContracts\NameResolverInterface;

abstract class AbstractInterfaceTypeFieldResolver extends AbstractFieldResolver implements InterfaceTypeFieldResolverInterface
{
    use AttachableExtensionTrait;
    use WithVersionConstraintFieldOrDirectiveResolverTrait;
    use FieldOrDirectiveSchemaDefinitionResolverTrait;
    use CheckDangerouslyDynamicScalarFieldOrDirectiveResolverTrait;

    /** @var array<string, array> */
    protected array $schemaDefinitionForFieldCache = [];
    /** @var array<string, string|null> */
    protected array $consolidatedFieldDescriptionCache = [];
    /** @var array<string, array<string,mixed>> */
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
    /** @var array<string, array<string,mixed>> */
    protected array $consolidatedFieldArgExtensionsCache = [];
    /** @var array<string, array<string, mixed>> */
    protected array $schemaFieldArgsCache = [];

    /**
     * @var InterfaceTypeResolverInterface[]|null
     */
    protected ?array $partiallyImplementedInterfaceTypeResolvers = null;

    private ?NameResolverInterface $nameResolver = null;
    private ?CMSServiceInterface $cmsService = null;
    private ?SchemaNamespacingServiceInterface $schemaNamespacingService = null;
    private ?TypeRegistryInterface $typeRegistry = null;
    private ?SchemaDefinitionServiceInterface $schemaDefinitionService = null;
    private ?DangerouslyDynamicScalarTypeResolver $dangerouslyDynamicScalarTypeResolver = null;
    private ?AttachableExtensionManagerInterface $attachableExtensionManager = null;

    final public function setNameResolver(NameResolverInterface $nameResolver): void
    {
        $this->nameResolver = $nameResolver;
    }
    final protected function getNameResolver(): NameResolverInterface
    {
        return $this->nameResolver ??= $this->instanceManager->getInstance(NameResolverInterface::class);
    }
    final public function setCMSService(CMSServiceInterface $cmsService): void
    {
        $this->cmsService = $cmsService;
    }
    final protected function getCMSService(): CMSServiceInterface
    {
        return $this->cmsService ??= $this->instanceManager->getInstance(CMSServiceInterface::class);
    }
    final public function setSchemaNamespacingService(SchemaNamespacingServiceInterface $schemaNamespacingService): void
    {
        $this->schemaNamespacingService = $schemaNamespacingService;
    }
    final protected function getSchemaNamespacingService(): SchemaNamespacingServiceInterface
    {
        return $this->schemaNamespacingService ??= $this->instanceManager->getInstance(SchemaNamespacingServiceInterface::class);
    }
    final public function setTypeRegistry(TypeRegistryInterface $typeRegistry): void
    {
        $this->typeRegistry = $typeRegistry;
    }
    final protected function getTypeRegistry(): TypeRegistryInterface
    {
        return $this->typeRegistry ??= $this->instanceManager->getInstance(TypeRegistryInterface::class);
    }
    final public function setSchemaDefinitionService(SchemaDefinitionServiceInterface $schemaDefinitionService): void
    {
        $this->schemaDefinitionService = $schemaDefinitionService;
    }
    final protected function getSchemaDefinitionService(): SchemaDefinitionServiceInterface
    {
        return $this->schemaDefinitionService ??= $this->instanceManager->getInstance(SchemaDefinitionServiceInterface::class);
    }
    final public function setDangerouslyDynamicScalarTypeResolver(DangerouslyDynamicScalarTypeResolver $dangerouslyDynamicScalarTypeResolver): void
    {
        $this->dangerouslyDynamicScalarTypeResolver = $dangerouslyDynamicScalarTypeResolver;
    }
    final protected function getDangerouslyDynamicScalarTypeResolver(): DangerouslyDynamicScalarTypeResolver
    {
        return $this->dangerouslyDynamicScalarTypeResolver ??= $this->instanceManager->getInstance(DangerouslyDynamicScalarTypeResolver::class);
    }
    final public function setAttachableExtensionManager(AttachableExtensionManagerInterface $attachableExtensionManager): void
    {
        $this->attachableExtensionManager = $attachableExtensionManager;
    }
    final protected function getAttachableExtensionManager(): AttachableExtensionManagerInterface
    {
        return $this->attachableExtensionManager ??= $this->instanceManager->getInstance(AttachableExtensionManagerInterface::class);
    }

    /**
     * The InterfaceTypes the InterfaceTypeFieldResolver adds fields to
     *
     * @return string[]
     */
    final public function getClassesToAttachTo(): array
    {
        return $this->getInterfaceTypeResolverClassesToAttachTo();
    }

    public function getFieldNamesToResolve(): array
    {
        return $this->getFieldNamesToImplement();
    }

    /**
     * The interfaces the fieldResolver implements
     *
     * @return InterfaceTypeFieldResolverInterface[]
     */
    public function getImplementedInterfaceTypeFieldResolvers(): array
    {
        return [];
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
        if ($this->partiallyImplementedInterfaceTypeResolvers === null) {
            // Search all the InterfaceTypeResolvers who either are, or inherit from,
            // any class from getInterfaceTypeResolverClassesToAttachTo
            $this->partiallyImplementedInterfaceTypeResolvers = [];
            $interfaceTypeResolverClassesToAttachTo = $this->getInterfaceTypeResolverClassesToAttachTo();
            $interfaceTypeResolvers = $this->getTypeRegistry()->getInterfaceTypeResolvers();
            foreach ($interfaceTypeResolvers as $interfaceTypeResolver) {
                $interfaceTypeResolverClass = get_class($interfaceTypeResolver);
                foreach ($interfaceTypeResolverClassesToAttachTo as $interfaceTypeResolverClassToAttachTo) {
                    if (
                        $interfaceTypeResolverClass === $interfaceTypeResolverClassToAttachTo
                        || in_array($interfaceTypeResolverClassToAttachTo, class_parents($interfaceTypeResolverClass))
                    ) {
                        $this->partiallyImplementedInterfaceTypeResolvers[] = $interfaceTypeResolver;
                        break;
                    }
                }
            }
        }
        return $this->partiallyImplementedInterfaceTypeResolvers;
    }

    /**
     * By default, the resolver is this same object, unless function
     * `getInterfaceTypeFieldSchemaDefinitionResolver` is
     * implemented
     */
    protected function getSchemaDefinitionResolver(string $fieldName): InterfaceTypeFieldSchemaDefinitionResolverInterface
    {
        if ($interfaceTypeFieldSchemaDefinitionResolver = $this->getInterfaceTypeFieldSchemaDefinitionResolver($fieldName)) {
            return $interfaceTypeFieldSchemaDefinitionResolver;
        }
        return $this;
    }

    /**
     * Retrieve the InterfaceTypeFieldSchemaDefinitionResolverInterface
     * By default, if the InterfaceTypeFieldResolver implements an interface,
     * it is used as SchemaDefinitionResolver for the matching fields
     */
    protected function getInterfaceTypeFieldSchemaDefinitionResolver(string $fieldName): ?InterfaceTypeFieldSchemaDefinitionResolverInterface
    {
        foreach ($this->getImplementedInterfaceTypeFieldResolvers() as $implementedInterfaceTypeFieldResolver) {
            if (!in_array($fieldName, $implementedInterfaceTypeFieldResolver->getFieldNamesToImplement())) {
                continue;
            }
            /** @var InterfaceTypeFieldSchemaDefinitionResolverInterface */
            return $implementedInterfaceTypeFieldResolver;
        }
        return null;
    }

    public function getFieldTypeResolver(string $fieldName): ConcreteTypeResolverInterface
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($fieldName);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getFieldTypeResolver($fieldName);
        }
        return $this->getSchemaDefinitionService()->getDefaultConcreteTypeResolver();
    }

    public function getFieldDescription(string $fieldName): ?string
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($fieldName);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getFieldDescription($fieldName);
        }
        return null;
    }

    public function getFieldTypeModifiers(string $fieldName): int
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($fieldName);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getFieldTypeModifiers($fieldName);
        }
        return SchemaTypeModifiers::NONE;
    }

    public function getFieldDeprecationMessage(string $fieldName): ?string
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($fieldName);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getFieldDeprecationMessage($fieldName);
        }
        return null;
    }

    /**
     * @return array<string, InputTypeResolverInterface>
     */
    public function getFieldArgNameTypeResolvers(string $fieldName): array
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($fieldName);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getFieldArgNameTypeResolvers($fieldName);
        }
        return [];
    }

    /**
     * @return string[]
     */
    public function getAdminFieldArgNames(string $fieldName): array
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($fieldName);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getAdminFieldArgNames($fieldName);
        }
        return [];
    }

    public function getFieldArgDescription(string $fieldName, string $fieldArgName): ?string
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($fieldName);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getFieldArgDescription($fieldName, $fieldArgName);
        }
        return null;
    }

    public function getFieldArgDefaultValue(string $fieldName, string $fieldArgName): mixed
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($fieldName);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getFieldArgDefaultValue($fieldName, $fieldArgName);
        }
        return null;
    }

    public function getFieldArgTypeModifiers(string $fieldName, string $fieldArgName): int
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($fieldName);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getFieldArgTypeModifiers($fieldName, $fieldArgName);
        }
        return SchemaTypeModifiers::NONE;
    }

    public function isFieldAMutation(string $fieldName): bool
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($fieldName);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->isFieldAMutation($fieldName);
        }
        return false;
    }

    /**
     * Consolidation of the schema field arguments. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     */
    final public function getConsolidatedFieldArgNameTypeResolvers(string $fieldName): array
    {
        // Cache the result
        $cacheKey = $fieldName;
        if (array_key_exists($cacheKey, $this->consolidatedFieldArgNameTypeResolversCache)) {
            return $this->consolidatedFieldArgNameTypeResolversCache[$cacheKey];
        }

        /**
         * Allow to override/extend the inputs (eg: module "Post Categories" can add
         * input "categories" to field "Root.createPost")
         */
        $consolidatedFieldArgNameTypeResolvers = $this->getHooksAPI()->applyFilters(
            HookNames::INTERFACE_TYPE_FIELD_ARG_NAME_TYPE_RESOLVERS,
            $this->getFieldArgNameTypeResolvers($fieldName),
            $this,
            $fieldName,
        );

        // Exclude the admin field args, if "Admin" Schema is not enabled
        /** @var ComponentConfiguration */
        $componentConfiguration = \PoP\Root\Managers\ComponentManager::getComponent(Component::class)->getConfiguration();
        if (!$componentConfiguration->enableAdminSchema()) {
            $adminFieldArgNames = $this->getConsolidatedAdminFieldArgNames($fieldName);
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
    final public function getConsolidatedAdminFieldArgNames(string $fieldName): array
    {
        // Cache the result
        $cacheKey = $fieldName;
        if (array_key_exists($cacheKey, $this->consolidatedAdminFieldArgNamesCache)) {
            return $this->consolidatedAdminFieldArgNamesCache[$cacheKey];
        }
        $this->consolidatedAdminFieldArgNamesCache[$cacheKey] = $this->getHooksAPI()->applyFilters(
            HookNames::INTERFACE_TYPE_FIELD_ARG_NAME_TYPE_RESOLVERS,
            $this->getAdminFieldArgNames($fieldName),
            $this,
            $fieldName,
        );
        return $this->consolidatedAdminFieldArgNamesCache[$cacheKey];
    }

    /**
     * Consolidation of the schema field arguments. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     */
    final public function getConsolidatedFieldArgDescription(string $fieldName, string $fieldArgName): ?string
    {
        // Cache the result
        $cacheKey = $fieldName . '(' . $fieldArgName . ':)';
        if (array_key_exists($cacheKey, $this->consolidatedFieldArgDescriptionCache)) {
            return $this->consolidatedFieldArgDescriptionCache[$cacheKey];
        }
        $this->consolidatedFieldArgDescriptionCache[$cacheKey] = $this->getHooksAPI()->applyFilters(
            HookNames::INTERFACE_TYPE_FIELD_ARG_DESCRIPTION,
            $this->getFieldArgDescription($fieldName, $fieldArgName),
            $this,
            $fieldName,
            $fieldArgName,
        );
        return $this->consolidatedFieldArgDescriptionCache[$cacheKey];
    }

    /**
     * Consolidation of the schema field arguments. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     */
    final public function getConsolidatedFieldArgDefaultValue(string $fieldName, string $fieldArgName): mixed
    {
        // Cache the result
        $cacheKey = $fieldName . '(' . $fieldArgName . ':)';
        if (array_key_exists($cacheKey, $this->consolidatedFieldArgDefaultValueCache)) {
            return $this->consolidatedFieldArgDefaultValueCache[$cacheKey];
        }
        $this->consolidatedFieldArgDefaultValueCache[$cacheKey] = $this->getHooksAPI()->applyFilters(
            HookNames::INTERFACE_TYPE_FIELD_ARG_DEFAULT_VALUE,
            $this->getFieldArgDefaultValue($fieldName, $fieldArgName),
            $this,
            $fieldName,
            $fieldArgName,
        );
        return $this->consolidatedFieldArgDefaultValueCache[$cacheKey];
    }

    /**
     * Consolidation of the schema field arguments. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     */
    final public function getConsolidatedFieldArgTypeModifiers(string $fieldName, string $fieldArgName): int
    {
        // Cache the result
        $cacheKey = $fieldName . '(' . $fieldArgName . ':)';
        if (array_key_exists($cacheKey, $this->consolidatedFieldArgTypeModifiersCache)) {
            return $this->consolidatedFieldArgTypeModifiersCache[$cacheKey];
        }
        $this->consolidatedFieldArgTypeModifiersCache[$cacheKey] = $this->getHooksAPI()->applyFilters(
            HookNames::INTERFACE_TYPE_FIELD_ARG_TYPE_MODIFIERS,
            $this->getFieldArgTypeModifiers($fieldName, $fieldArgName),
            $this,
            $fieldName,
            $fieldArgName,
        );
        return $this->consolidatedFieldArgTypeModifiersCache[$cacheKey];
    }

    /**
     * Validate the constraints for a field argument
     *
     * @return string[] Error messages
     */
    public function validateFieldArgValue(
        string $fieldName,
        string $fieldArgName,
        mixed $fieldArgValue
    ): array {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($fieldName);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->validateFieldArgValue($fieldName, $fieldArgName, $fieldArgValue);
        }
        return [];
    }

    /**
     * Get the "schema" properties as for the fieldName
     */
    final public function getFieldSchemaDefinition(string $fieldName): array
    {
        // First check if the value was cached
        if (!isset($this->schemaDefinitionForFieldCache[$fieldName])) {
            $this->schemaDefinitionForFieldCache[$fieldName] = $this->doGetFieldSchemaDefinition($fieldName);
        }
        return $this->schemaDefinitionForFieldCache[$fieldName];
    }

    /**
     * Get the "schema" properties as for the fieldName
     */
    final protected function doGetFieldSchemaDefinition(string $fieldName): array
    {
        $fieldTypeResolver = $this->getFieldTypeResolver($fieldName);
        $fieldDescription =
            $this->getConsolidatedFieldDescription($fieldName)
            ?? $fieldTypeResolver->getTypeDescription();
        $schemaDefinition = $this->getFieldTypeSchemaDefinition(
            $fieldName,
            // This method has no "Consolidated" because it makes no sense
            $fieldTypeResolver,
            $fieldDescription,
            // This method has no "Consolidated" because it makes no sense
            $this->getFieldTypeModifiers($fieldName),
            $this->getConsolidatedFieldDeprecationMessage($fieldName),
        );
        $schemaDefinition[SchemaDefinition::EXTENSIONS] = $this->getConsolidatedFieldExtensionsSchemaDefinition($fieldName);

        if ($args = $this->getFieldArgsSchemaDefinition($fieldName)) {
            $schemaDefinition[SchemaDefinition::ARGS] = $args;
        }

        return $schemaDefinition;
    }

    /**
     * Watch out: The same extensions must be present for both
     * the ObjectType and the InterfaceType!
     *
     * @return array<string, mixed>
     */
    protected function getFieldExtensionsSchemaDefinition(string $fieldName): array
    {
        return [
            SchemaDefinition::FIELD_IS_MUTATION => $this->isFieldAMutation($fieldName),
            SchemaDefinition::IS_ADMIN_ELEMENT => in_array($fieldName, $this->getAdminFieldNames()),
        ];
    }

    /**
     * Consolidation of the schema field arguments. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     */
    final protected function getConsolidatedFieldExtensionsSchemaDefinition(string $fieldName): array
    {
        // Cache the result
        $cacheKey = $fieldName;
        if (array_key_exists($cacheKey, $this->consolidatedFieldExtensionsCache)) {
            return $this->consolidatedFieldExtensionsCache[$cacheKey];
        }
        $this->consolidatedFieldExtensionsCache[$cacheKey] = $this->getHooksAPI()->applyFilters(
            HookNames::INTERFACE_TYPE_FIELD_EXTENSIONS,
            $this->getFieldExtensionsSchemaDefinition($fieldName),
            $this,
            $fieldName,
        );
        return $this->consolidatedFieldExtensionsCache[$cacheKey];
    }

    /**
     * Consolidation of the schema field arguments. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     */
    final public function getConsolidatedFieldDescription(string $fieldName): ?string
    {
        // Cache the result
        $cacheKey = $fieldName;
        if (array_key_exists($cacheKey, $this->consolidatedFieldDescriptionCache)) {
            return $this->consolidatedFieldDescriptionCache[$cacheKey];
        }
        $this->consolidatedFieldDescriptionCache[$cacheKey] = $this->getHooksAPI()->applyFilters(
            HookNames::INTERFACE_TYPE_FIELD_DESCRIPTION,
            $this->getFieldDescription($fieldName),
            $this,
            $fieldName,
        );
        return $this->consolidatedFieldDescriptionCache[$cacheKey];
    }

    /**
     * Consolidation of the schema field arguments. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     */
    final public function getConsolidatedFieldDeprecationMessage(string $fieldName): ?string
    {
        // Cache the result
        $cacheKey = $fieldName;
        if (array_key_exists($cacheKey, $this->consolidatedFieldDeprecationMessageCache)) {
            return $this->consolidatedFieldDeprecationMessageCache[$cacheKey];
        }
        $this->consolidatedFieldDeprecationMessageCache[$cacheKey] = $this->getHooksAPI()->applyFilters(
            HookNames::INTERFACE_TYPE_FIELD_DEPRECATION_MESSAGE,
            $this->getFieldDeprecationMessage($fieldName),
            $this,
            $fieldName,
        );
        return $this->consolidatedFieldDeprecationMessageCache[$cacheKey];
    }

    /**
     * Consolidation of the schema field arguments. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     */
    final public function getFieldArgsSchemaDefinition(string $fieldName): array
    {
        // Cache the result
        $cacheKey = $fieldName;
        if (array_key_exists($cacheKey, $this->schemaFieldArgsCache)) {
            return $this->schemaFieldArgsCache[$cacheKey];
        }
        $schemaFieldArgs = [];
        /** @var ComponentConfiguration */
        $componentConfiguration = \PoP\Root\Managers\ComponentManager::getComponent(Component::class)->getConfiguration();
        $skipExposingDangerouslyDynamicScalarTypeInSchema = $componentConfiguration->skipExposingDangerouslyDynamicScalarTypeInSchema();
        $consolidatedFieldArgNameTypeResolvers = $this->getConsolidatedFieldArgNameTypeResolvers($fieldName);
        foreach ($consolidatedFieldArgNameTypeResolvers as $fieldArgName => $fieldArgInputTypeResolver) {
            /**
             * `DangerouslyDynamic` is a special scalar type which is not coerced or validated.
             * If disabled, then do not expose the directive args of this type
             */
            if (
                $skipExposingDangerouslyDynamicScalarTypeInSchema
                && $fieldArgInputTypeResolver === $this->getDangerouslyDynamicScalarTypeResolver()
            ) {
                continue;
            }
            if ($this->skipExposingFieldArgInSchema($fieldName, $fieldArgName)) {
                continue;
            }

            $fieldArgDescription =
                $this->getConsolidatedFieldArgDescription($fieldName, $fieldArgName)
                ?? $fieldArgInputTypeResolver->getTypeDescription();
            $schemaFieldArgs[$fieldArgName] = $this->getFieldOrDirectiveArgTypeSchemaDefinition(
                $fieldArgName,
                $fieldArgInputTypeResolver,
                $fieldArgDescription,
                $this->getConsolidatedFieldArgDefaultValue($fieldName, $fieldArgName),
                $this->getConsolidatedFieldArgTypeModifiers($fieldName, $fieldArgName),
            );
            $schemaFieldArgs[$fieldArgName][SchemaDefinition::EXTENSIONS] = $this->getConsolidatedFieldArgExtensionsSchemaDefinition($fieldName, $fieldArgName);
        }
        $this->schemaFieldArgsCache[$cacheKey] = $schemaFieldArgs;
        return $this->schemaFieldArgsCache[$cacheKey];
    }

    protected function getFieldArgExtensionsSchemaDefinition(string $fieldName, string $fieldArgName): array
    {
        $adminFieldArgNames = $this->getConsolidatedAdminFieldArgNames($fieldName);
        return [
            SchemaDefinition::IS_ADMIN_ELEMENT => in_array($fieldArgName, $adminFieldArgNames),
        ];
    }

    /**
     * Consolidation of the schema field arguments. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     */
    final protected function getConsolidatedFieldArgExtensionsSchemaDefinition(string $fieldName, string $fieldArgName): array
    {
        // Cache the result
        $cacheKey = $fieldName . '(' . $fieldArgName . ':)';
        if (array_key_exists($cacheKey, $this->consolidatedFieldArgExtensionsCache)) {
            return $this->consolidatedFieldArgExtensionsCache[$cacheKey];
        }
        $this->consolidatedFieldArgExtensionsCache[$cacheKey] = $this->getHooksAPI()->applyFilters(
            HookNames::INTERFACE_TYPE_FIELD_ARG_EXTENSIONS,
            $this->getFieldArgExtensionsSchemaDefinition($fieldName, $fieldArgName),
            $this,
            $fieldName,
            $fieldArgName,
        );
        return $this->consolidatedFieldArgExtensionsCache[$cacheKey];
    }

    /**
     * Fields may not be directly visible in the schema,
     * eg: because they are used only by the application, and must not
     * be exposed to the user (eg: "accessControlLists")
     */
    public function skipExposingFieldInSchema(string $fieldName): bool
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = \PoP\Root\Managers\ComponentManager::getComponent(Component::class)->getConfiguration();
        if ($componentConfiguration->skipExposingDangerouslyDynamicScalarTypeInSchema()) {
            /**
             * If `DangerouslyDynamic` is disabled, do not expose the field if either:
             *
             *   1. its type is `DangerouslyDynamic`
             *   2. it has any mandatory argument of type `DangerouslyDynamic`
             */
            $consolidatedFieldArgNames = array_keys($this->getConsolidatedFieldArgNameTypeResolvers($fieldName));
            $consolidatedFieldArgsTypeModifiers = [];
            foreach ($consolidatedFieldArgNames as $fieldArgName) {
                $consolidatedFieldArgsTypeModifiers[$fieldArgName] = $this->getConsolidatedFieldArgTypeModifiers($fieldName, $fieldArgName);
            }
            if (
                $this->isDangerouslyDynamicScalarFieldType(
                    $this->getFieldTypeResolver($fieldName),
                    $this->getConsolidatedFieldArgNameTypeResolvers($fieldName),
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
    public function skipExposingFieldArgInSchema(string $fieldName, string $fieldArgName): bool
    {
        return false;
    }
}
