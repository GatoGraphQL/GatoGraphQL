<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers\InterfaceType;

use PoP\ComponentModel\AttachableExtensions\AttachableExtensionTrait;
use PoP\ComponentModel\ComponentConfiguration;
use PoP\ComponentModel\FieldResolvers\AbstractFieldResolver;
use PoP\ComponentModel\FieldResolvers\ObjectType\HookNames;
use PoP\ComponentModel\Registries\TypeRegistryInterface;
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
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractInterfaceTypeFieldResolver extends AbstractFieldResolver implements InterfaceTypeFieldResolverInterface
{
    use AttachableExtensionTrait;
    use WithVersionConstraintFieldOrDirectiveResolverTrait;
    use FieldOrDirectiveSchemaDefinitionResolverTrait;

    /** @var array<string, array> */
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
     * @var InterfaceTypeResolverInterface[]|null
     */
    protected ?array $partiallyImplementedInterfaceTypeResolvers = null;

    private ?NameResolverInterface $nameResolver = null;
    private ?CMSServiceInterface $cmsService = null;
    private ?SchemaNamespacingServiceInterface $schemaNamespacingService = null;
    private ?TypeRegistryInterface $typeRegistry = null;
    private ?SchemaDefinitionServiceInterface $schemaDefinitionService = null;
    private ?DangerouslyDynamicScalarTypeResolver $dangerouslyDynamicScalarTypeResolver = null;

    public function setNameResolver(NameResolverInterface $nameResolver): void
    {
        $this->nameResolver = $nameResolver;
    }
    protected function getNameResolver(): NameResolverInterface
    {
        return $this->nameResolver ??= $this->instanceManager->getInstance(NameResolverInterface::class);
    }
    public function setCMSService(CMSServiceInterface $cmsService): void
    {
        $this->cmsService = $cmsService;
    }
    protected function getCMSService(): CMSServiceInterface
    {
        return $this->cmsService ??= $this->instanceManager->getInstance(CMSServiceInterface::class);
    }
    public function setSchemaNamespacingService(SchemaNamespacingServiceInterface $schemaNamespacingService): void
    {
        $this->schemaNamespacingService = $schemaNamespacingService;
    }
    protected function getSchemaNamespacingService(): SchemaNamespacingServiceInterface
    {
        return $this->schemaNamespacingService ??= $this->instanceManager->getInstance(SchemaNamespacingServiceInterface::class);
    }
    public function setTypeRegistry(TypeRegistryInterface $typeRegistry): void
    {
        $this->typeRegistry = $typeRegistry;
    }
    protected function getTypeRegistry(): TypeRegistryInterface
    {
        return $this->typeRegistry ??= $this->instanceManager->getInstance(TypeRegistryInterface::class);
    }
    public function setSchemaDefinitionService(SchemaDefinitionServiceInterface $schemaDefinitionService): void
    {
        $this->schemaDefinitionService = $schemaDefinitionService;
    }
    protected function getSchemaDefinitionService(): SchemaDefinitionServiceInterface
    {
        return $this->schemaDefinitionService ??= $this->instanceManager->getInstance(SchemaDefinitionServiceInterface::class);
    }
    public function setDangerouslyDynamicScalarTypeResolver(DangerouslyDynamicScalarTypeResolver $dangerouslyDynamicScalarTypeResolver): void
    {
        $this->dangerouslyDynamicScalarTypeResolver = $dangerouslyDynamicScalarTypeResolver;
    }
    protected function getDangerouslyDynamicScalarTypeResolver(): DangerouslyDynamicScalarTypeResolver
    {
        return $this->dangerouslyDynamicScalarTypeResolver ??= $this->instanceManager->getInstance(DangerouslyDynamicScalarTypeResolver::class);
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

        $fieldArgNameTypeResolvers = $this->getFieldArgNameTypeResolvers($fieldName);

        /**
         * Allow to override/extend the inputs (eg: module "Post Categories" can add
         * input "categories" to field "Root.createPost")
         */
        $consolidatedFieldArgNameTypeResolvers = $this->getHooksAPI()->applyFilters(
            HookNames::INTERFACE_TYPE_FIELD_ARG_NAME_TYPE_RESOLVERS,
            $fieldArgNameTypeResolvers,
            $this,
            $fieldName,
        );
        $this->consolidatedFieldArgNameTypeResolversCache[$cacheKey] = $consolidatedFieldArgNameTypeResolvers;
        return $this->consolidatedFieldArgNameTypeResolversCache[$cacheKey];
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
    public function validateFieldArgument(
        string $fieldName,
        string $fieldArgName,
        mixed $fieldArgValue
    ): array {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($fieldName);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->validateFieldArgument($fieldName, $fieldArgName, $fieldArgValue);
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
        $schemaDefinition = $this->getFieldTypeSchemaDefinition(
            $fieldName,
            // This method has no "Consolidated" because it makes no sense
            $this->getFieldTypeResolver($fieldName),
            $this->getConsolidatedFieldDescription($fieldName),
            // This method has no "Consolidated" because it makes no sense
            $this->getFieldTypeModifiers($fieldName),
            $this->getConsolidatedFieldDeprecationMessage($fieldName),
        );

        if ($args = $this->getFieldArgsSchemaDefinition($fieldName)) {
            $schemaDefinition[SchemaDefinition::ARGS] = $args;
        }

        return $schemaDefinition;
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
        $skipExposingDangerouslyDynamicScalarTypeInSchema = ComponentConfiguration::skipExposingDangerouslyDynamicScalarTypeInSchema();
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

            $schemaFieldArgs[$fieldArgName] = $this->getFieldOrDirectiveArgTypeSchemaDefinition(
                $fieldArgName,
                $fieldArgInputTypeResolver,
                $this->getConsolidatedFieldArgDescription($fieldName, $fieldArgName),
                $this->getConsolidatedFieldArgDefaultValue($fieldName, $fieldArgName),
                $this->getConsolidatedFieldArgTypeModifiers($fieldName, $fieldArgName),
            );
        }
        $this->schemaFieldArgsCache[$cacheKey] = $schemaFieldArgs;
        return $this->schemaFieldArgsCache[$cacheKey];
    }

    /**
     * Fields may not be directly visible in the schema,
     * eg: because they are used only by the application, and must not
     * be exposed to the user (eg: "accessControlLists")
     */
    public function skipExposingFieldInSchema(string $fieldName): bool
    {
        if (ComponentConfiguration::skipExposingDangerouslyDynamicScalarTypeInSchema()) {
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
