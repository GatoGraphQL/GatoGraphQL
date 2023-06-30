<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers\InterfaceType;

use PoP\ComponentModel\AttachableExtensions\AttachableExtensionManagerInterface;
use PoP\ComponentModel\AttachableExtensions\AttachableExtensionTrait;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\AbstractFieldResolver;
use PoP\ComponentModel\Module;
use PoP\ComponentModel\ModuleConfiguration;
use PoP\ComponentModel\Registries\TypeRegistryInterface;
use PoP\ComponentModel\Resolvers\CheckDangerouslyNonSpecificScalarTypeFieldOrFieldDirectiveResolverTrait;
use PoP\ComponentModel\Resolvers\FieldOrDirectiveSchemaDefinitionResolverTrait;
use PoP\ComponentModel\Resolvers\WithVersionConstraintFieldOrFieldDirectiveResolverTrait;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaDefinitionServiceInterface;
use PoP\ComponentModel\Schema\SchemaNamespacingServiceInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\DangerouslyNonSpecificScalarTypeScalarTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Root\App;

abstract class AbstractInterfaceTypeFieldResolver extends AbstractFieldResolver implements InterfaceTypeFieldResolverInterface
{
    use AttachableExtensionTrait;
    use WithVersionConstraintFieldOrFieldDirectiveResolverTrait;
    use FieldOrDirectiveSchemaDefinitionResolverTrait;
    use CheckDangerouslyNonSpecificScalarTypeFieldOrFieldDirectiveResolverTrait;

    /** @var array<string,array<string,mixed>> */
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
    protected array $consolidatedSensitiveFieldArgNamesCache = [];
    /** @var array<string,string|null> */
    protected array $consolidatedFieldArgDescriptionCache = [];
    /** @var array<string,string|null> */
    protected array $consolidatedFieldArgDeprecationMessageCache = [];
    /** @var array<string,mixed> */
    protected array $consolidatedFieldArgDefaultValueCache = [];
    /** @var array<string,int> */
    protected array $consolidatedFieldArgTypeModifiersCache = [];
    /** @var array<string,array<string,mixed>> */
    protected array $consolidatedFieldArgExtensionsCache = [];
    /** @var array<string,array<string,mixed>> */
    protected array $schemaFieldArgsCache = [];

    /**
     * @var InterfaceTypeResolverInterface[]|null
     */
    protected ?array $partiallyImplementedInterfaceTypeResolvers = null;

    private ?NameResolverInterface $nameResolver = null;
    private ?SchemaNamespacingServiceInterface $schemaNamespacingService = null;
    private ?TypeRegistryInterface $typeRegistry = null;
    private ?SchemaDefinitionServiceInterface $schemaDefinitionService = null;
    private ?DangerouslyNonSpecificScalarTypeScalarTypeResolver $dangerouslyNonSpecificScalarTypeScalarTypeResolver = null;
    private ?AttachableExtensionManagerInterface $attachableExtensionManager = null;

    final public function setNameResolver(NameResolverInterface $nameResolver): void
    {
        $this->nameResolver = $nameResolver;
    }
    final protected function getNameResolver(): NameResolverInterface
    {
        if ($this->nameResolver === null) {
            /** @var NameResolverInterface */
            $nameResolver = $this->instanceManager->getInstance(NameResolverInterface::class);
            $this->nameResolver = $nameResolver;
        }
        return $this->nameResolver;
    }
    final public function setSchemaNamespacingService(SchemaNamespacingServiceInterface $schemaNamespacingService): void
    {
        $this->schemaNamespacingService = $schemaNamespacingService;
    }
    final protected function getSchemaNamespacingService(): SchemaNamespacingServiceInterface
    {
        if ($this->schemaNamespacingService === null) {
            /** @var SchemaNamespacingServiceInterface */
            $schemaNamespacingService = $this->instanceManager->getInstance(SchemaNamespacingServiceInterface::class);
            $this->schemaNamespacingService = $schemaNamespacingService;
        }
        return $this->schemaNamespacingService;
    }
    final public function setTypeRegistry(TypeRegistryInterface $typeRegistry): void
    {
        $this->typeRegistry = $typeRegistry;
    }
    final protected function getTypeRegistry(): TypeRegistryInterface
    {
        if ($this->typeRegistry === null) {
            /** @var TypeRegistryInterface */
            $typeRegistry = $this->instanceManager->getInstance(TypeRegistryInterface::class);
            $this->typeRegistry = $typeRegistry;
        }
        return $this->typeRegistry;
    }
    final public function setSchemaDefinitionService(SchemaDefinitionServiceInterface $schemaDefinitionService): void
    {
        $this->schemaDefinitionService = $schemaDefinitionService;
    }
    final protected function getSchemaDefinitionService(): SchemaDefinitionServiceInterface
    {
        if ($this->schemaDefinitionService === null) {
            /** @var SchemaDefinitionServiceInterface */
            $schemaDefinitionService = $this->instanceManager->getInstance(SchemaDefinitionServiceInterface::class);
            $this->schemaDefinitionService = $schemaDefinitionService;
        }
        return $this->schemaDefinitionService;
    }
    final public function setDangerouslyNonSpecificScalarTypeScalarTypeResolver(DangerouslyNonSpecificScalarTypeScalarTypeResolver $dangerouslyNonSpecificScalarTypeScalarTypeResolver): void
    {
        $this->dangerouslyNonSpecificScalarTypeScalarTypeResolver = $dangerouslyNonSpecificScalarTypeScalarTypeResolver;
    }
    final protected function getDangerouslyNonSpecificScalarTypeScalarTypeResolver(): DangerouslyNonSpecificScalarTypeScalarTypeResolver
    {
        if ($this->dangerouslyNonSpecificScalarTypeScalarTypeResolver === null) {
            /** @var DangerouslyNonSpecificScalarTypeScalarTypeResolver */
            $dangerouslyNonSpecificScalarTypeScalarTypeResolver = $this->instanceManager->getInstance(DangerouslyNonSpecificScalarTypeScalarTypeResolver::class);
            $this->dangerouslyNonSpecificScalarTypeScalarTypeResolver = $dangerouslyNonSpecificScalarTypeScalarTypeResolver;
        }
        return $this->dangerouslyNonSpecificScalarTypeScalarTypeResolver;
    }
    final public function setAttachableExtensionManager(AttachableExtensionManagerInterface $attachableExtensionManager): void
    {
        $this->attachableExtensionManager = $attachableExtensionManager;
    }
    final protected function getAttachableExtensionManager(): AttachableExtensionManagerInterface
    {
        if ($this->attachableExtensionManager === null) {
            /** @var AttachableExtensionManagerInterface */
            $attachableExtensionManager = $this->instanceManager->getInstance(AttachableExtensionManagerInterface::class);
            $this->attachableExtensionManager = $attachableExtensionManager;
        }
        return $this->attachableExtensionManager;
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

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        return $this->getFieldNamesToImplement();
    }

    /**
     * The interfaces the fieldResolver implements
     *
     * @return array<InterfaceTypeFieldResolverInterface>
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
                    $interfaceTypeResolverClassParents = class_parents($interfaceTypeResolverClass);
                    if (
                        $interfaceTypeResolverClass === $interfaceTypeResolverClassToAttachTo
                        || ($interfaceTypeResolverClassParents !== false && in_array($interfaceTypeResolverClassToAttachTo, $interfaceTypeResolverClassParents))
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
     * @return array<string,InputTypeResolverInterface>
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
    public function getSensitiveFieldArgNames(string $fieldName): array
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($fieldName);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getSensitiveFieldArgNames($fieldName);
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

    public function isFieldGlobal(FieldInterface|string $fieldOrFieldName): bool
    {
        if ($fieldOrFieldName instanceof FieldInterface) {
            $field = $fieldOrFieldName;
            $fieldName = $field->getName();
        } else {
            $fieldName = $fieldOrFieldName;
        }
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($fieldName);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->isFieldGlobal($fieldOrFieldName);
        }
        return false;
    }

    public function isFieldAMutation(FieldInterface|string $fieldOrFieldName): bool
    {
        if ($fieldOrFieldName instanceof FieldInterface) {
            $field = $fieldOrFieldName;
            $fieldName = $field->getName();
        } else {
            $fieldName = $fieldOrFieldName;
        }
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($fieldName);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->isFieldAMutation($fieldOrFieldName);
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
        $consolidatedFieldArgNameTypeResolvers = App::applyFilters(
            HookNames::INTERFACE_TYPE_FIELD_ARG_NAME_TYPE_RESOLVERS,
            $this->getFieldArgNameTypeResolvers($fieldName),
            $this,
            $fieldName,
        );

        // Exclude the sensitive field args, if "Admin" Schema is not enabled
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if (!$moduleConfiguration->exposeSensitiveDataInSchema()) {
            $sensitiveFieldArgNames = $this->getConsolidatedSensitiveFieldArgNames($fieldName);
            $consolidatedFieldArgNameTypeResolvers = array_filter(
                $consolidatedFieldArgNameTypeResolvers,
                fn (string $fieldArgName) => !in_array($fieldArgName, $sensitiveFieldArgNames),
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
    final public function getConsolidatedSensitiveFieldArgNames(string $fieldName): array
    {
        // Cache the result
        $cacheKey = $fieldName;
        if (array_key_exists($cacheKey, $this->consolidatedSensitiveFieldArgNamesCache)) {
            return $this->consolidatedSensitiveFieldArgNamesCache[$cacheKey];
        }
        $this->consolidatedSensitiveFieldArgNamesCache[$cacheKey] = App::applyFilters(
            HookNames::INTERFACE_TYPE_FIELD_ARG_NAME_TYPE_RESOLVERS,
            $this->getSensitiveFieldArgNames($fieldName),
            $this,
            $fieldName,
        );
        return $this->consolidatedSensitiveFieldArgNamesCache[$cacheKey];
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
        $this->consolidatedFieldArgDescriptionCache[$cacheKey] = App::applyFilters(
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
        $this->consolidatedFieldArgDefaultValueCache[$cacheKey] = App::applyFilters(
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
        $this->consolidatedFieldArgTypeModifiersCache[$cacheKey] = App::applyFilters(
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
     */
    public function validateFieldArgValue(
        string $fieldName,
        string $fieldArgName,
        mixed $fieldArgValue,
        AstInterface $astNode,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($fieldName);
        if ($schemaDefinitionResolver !== $this) {
            $schemaDefinitionResolver->validateFieldArgValue($fieldName, $fieldArgName, $fieldArgValue, $astNode, $objectTypeFieldResolutionFeedbackStore);
        }
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
     *
     * @return array<string,mixed>
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
     * @return array<string,mixed>
     */
    protected function getFieldExtensionsSchemaDefinition(string $fieldName): array
    {
        return [
            SchemaDefinition::FIELD_IS_GLOBAL => $this->isFieldGlobal($fieldName),
            SchemaDefinition::FIELD_IS_MUTATION => $this->isFieldAMutation($fieldName),
            SchemaDefinition::IS_SENSITIVE_DATA_ELEMENT => in_array($fieldName, $this->getSensitiveFieldNames()),
        ];
    }

    /**
     * Consolidation of the schema field arguments. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     *
     * @return array<string,mixed>
     */
    final protected function getConsolidatedFieldExtensionsSchemaDefinition(string $fieldName): array
    {
        // Cache the result
        $cacheKey = $fieldName;
        if (array_key_exists($cacheKey, $this->consolidatedFieldExtensionsCache)) {
            return $this->consolidatedFieldExtensionsCache[$cacheKey];
        }
        $this->consolidatedFieldExtensionsCache[$cacheKey] = App::applyFilters(
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
        $this->consolidatedFieldDescriptionCache[$cacheKey] = App::applyFilters(
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
        $this->consolidatedFieldDeprecationMessageCache[$cacheKey] = App::applyFilters(
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
     *
     * @return array<string,mixed>
     */
    final public function getFieldArgsSchemaDefinition(string $fieldName): array
    {
        // Cache the result
        $cacheKey = $fieldName;
        if (array_key_exists($cacheKey, $this->schemaFieldArgsCache)) {
            return $this->schemaFieldArgsCache[$cacheKey];
        }
        $schemaFieldArgs = [];
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $skipExposingDangerouslyNonSpecificScalarTypeTypeInSchema = $moduleConfiguration->skipExposingDangerouslyNonSpecificScalarTypeTypeInSchema();
        $consolidatedFieldArgNameTypeResolvers = $this->getConsolidatedFieldArgNameTypeResolvers($fieldName);
        foreach ($consolidatedFieldArgNameTypeResolvers as $fieldArgName => $fieldArgInputTypeResolver) {
            /**
             * `DangerouslyNonSpecificScalar` is a special scalar type which is not coerced or validated.
             * If disabled, then do not expose the directive args of this type
             */
            if (
                $skipExposingDangerouslyNonSpecificScalarTypeTypeInSchema
                && $fieldArgInputTypeResolver === $this->getDangerouslyNonSpecificScalarTypeScalarTypeResolver()
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

    /**
     * @return array<string,mixed>
     */
    protected function getFieldArgExtensionsSchemaDefinition(string $fieldName, string $fieldArgName): array
    {
        $sensitiveFieldArgNames = $this->getConsolidatedSensitiveFieldArgNames($fieldName);
        return [
            SchemaDefinition::IS_SENSITIVE_DATA_ELEMENT => in_array($fieldArgName, $sensitiveFieldArgNames),
        ];
    }

    /**
     * Consolidation of the schema field arguments. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     *
     * @return array<string,mixed>
     */
    final protected function getConsolidatedFieldArgExtensionsSchemaDefinition(string $fieldName, string $fieldArgName): array
    {
        // Cache the result
        $cacheKey = $fieldName . '(' . $fieldArgName . ':)';
        if (array_key_exists($cacheKey, $this->consolidatedFieldArgExtensionsCache)) {
            return $this->consolidatedFieldArgExtensionsCache[$cacheKey];
        }
        $this->consolidatedFieldArgExtensionsCache[$cacheKey] = App::applyFilters(
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
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if ($moduleConfiguration->skipExposingDangerouslyNonSpecificScalarTypeTypeInSchema()) {
            /**
             * If `DangerouslyNonSpecificScalar` is disabled, do not expose the field if either:
             *
             *   1. its type is `DangerouslyNonSpecificScalar`
             *   2. it has any mandatory argument of type `DangerouslyNonSpecificScalar`
             */
            $consolidatedFieldArgNames = array_keys($this->getConsolidatedFieldArgNameTypeResolvers($fieldName));
            $consolidatedFieldArgsTypeModifiers = [];
            foreach ($consolidatedFieldArgNames as $fieldArgName) {
                $consolidatedFieldArgsTypeModifiers[$fieldArgName] = $this->getConsolidatedFieldArgTypeModifiers($fieldName, $fieldArgName);
            }
            if (
                $this->isDangerouslyNonSpecificScalarTypeFieldType(
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
