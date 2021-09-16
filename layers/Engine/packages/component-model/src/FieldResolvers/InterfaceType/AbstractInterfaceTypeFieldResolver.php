<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers\InterfaceType;

use PoP\ComponentModel\AttachableExtensions\AttachableExtensionTrait;
use PoP\ComponentModel\Facades\Schema\SchemaDefinitionServiceFacade;
use PoP\ComponentModel\FieldResolvers\AbstractFieldResolver;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Registries\TypeRegistryInterface;
use PoP\ComponentModel\Resolvers\EnumTypeSchemaDefinitionResolverTrait;
use PoP\ComponentModel\Resolvers\WithVersionConstraintFieldOrDirectiveResolverTrait;
use PoP\ComponentModel\Schema\SchemaHelpers;
use PoP\ComponentModel\Schema\SchemaNamespacingServiceInterface;
use PoP\ComponentModel\TypeResolvers\EnumType\EnumTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\AnyScalarScalarTypeResolver;
use PoP\Engine\CMS\CMSServiceInterface;
use PoP\Hooks\HooksAPIInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Translation\TranslationAPIInterface;

abstract class AbstractInterfaceTypeFieldResolver extends AbstractFieldResolver implements InterfaceTypeFieldResolverInterface, InterfaceTypeFieldSchemaDefinitionResolverInterface
{
    use AttachableExtensionTrait;
    use WithVersionConstraintFieldOrDirectiveResolverTrait;
    use EnumTypeSchemaDefinitionResolverTrait;

    /**
     * @var InterfaceTypeResolverInterface[]|null
     */
    protected ?array $partiallyImplementedInterfaceTypeResolvers = null;

    public function __construct(
        TranslationAPIInterface $translationAPI,
        HooksAPIInterface $hooksAPI,
        InstanceManagerInterface $instanceManager,
        protected NameResolverInterface $nameResolver,
        protected CMSServiceInterface $cmsService,
        protected SchemaNamespacingServiceInterface $schemaNamespacingService,
        protected TypeRegistryInterface $typeRegistry,
    ) {
        parent::__construct(
            $translationAPI,
            $hooksAPI,
            $instanceManager,
        );
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

    public function getImplementedInterfaceTypeFieldResolverClasses(): array
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
            $interfaceTypeResolvers = $this->typeRegistry->getInterfaceTypeResolvers();
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
     * @return string[]
     */
    final public function getPartiallyImplementedInterfaceTypeResolverClasses(): array
    {
        return array_map(
            'get_class',
            $this->getPartiallyImplementedInterfaceTypeResolvers()
        );
    }

    /**
     * By default, the resolver is this same object, unless function
     * `getInterfaceTypeFieldSchemaDefinitionResolverClass` is
     * implemented
     */
    protected function getSchemaDefinitionResolver(string $fieldName): InterfaceTypeFieldSchemaDefinitionResolverInterface
    {
        if ($interfaceTypeFieldSchemaDefinitionResolverClass = $this->getInterfaceTypeFieldSchemaDefinitionResolverClass($fieldName)) {
            /** @var InterfaceTypeFieldSchemaDefinitionResolverInterface */
            return $this->instanceManager->getInstance($interfaceTypeFieldSchemaDefinitionResolverClass);
        }
        return $this;
    }

    /**
     * Retrieve the class of some InterfaceTypeFieldSchemaDefinitionResolverInterface
     * By default, if the InterfaceTypeFieldResolver implements an interface,
     * it is used as SchemaDefinitionResolver for the matching fields
     */
    protected function getInterfaceTypeFieldSchemaDefinitionResolverClass(string $fieldName): ?string
    {
        foreach ($this->getImplementedInterfaceTypeFieldResolverClasses() as $implementedInterfaceTypeFieldResolverClass) {
            /** @var InterfaceTypeFieldResolverInterface */
            $implementedInterfaceTypeFieldResolver = $this->instanceManager->getInstance($implementedInterfaceTypeFieldResolverClass);
            ;
            if (!in_array($fieldName, $implementedInterfaceTypeFieldResolver->getFieldNamesToImplement())) {
                continue;
            }
            return $implementedInterfaceTypeFieldResolverClass;
        }
        return null;
    }

    public function getSchemaFieldType(string $fieldName): string
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($fieldName);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getSchemaFieldType($fieldName);
        }

        $schemaDefinitionService = SchemaDefinitionServiceFacade::getInstance();
        return $schemaDefinitionService->getDefaultType();
    }

    public function getSchemaFieldTypeModifiers(string $fieldName): ?int
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($fieldName);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getSchemaFieldTypeModifiers($fieldName);
        }
        return null;
    }

    public function getSchemaFieldDescription(string $fieldName): ?string
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($fieldName);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getSchemaFieldDescription($fieldName);
        }
        return null;
    }

    public function getSchemaFieldArgs(string $fieldName): array
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($fieldName);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getSchemaFieldArgs($fieldName);
        }
        return [];
    }

    public function getSchemaFieldDeprecationDescription(string $fieldName, array $fieldArgs = []): ?string
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($fieldName);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getSchemaFieldDeprecationDescription($fieldName, $fieldArgs);
        }
        return null;
    }

    /**
     * By default, the field is a scalar of type AnyScalar
     */
    public function getFieldTypeResolverClass(string $fieldName): string
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($fieldName);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getFieldTypeResolverClass($fieldName);
        }
        $schemaDefinitionService = SchemaDefinitionServiceFacade::getInstance();
        return $schemaDefinitionService->getDefaultTypeResolverClass();
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

    public function addSchemaDefinitionForField(array &$schemaDefinition, string $fieldName): void
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($fieldName);
        if ($schemaDefinitionResolver !== $this) {
            $schemaDefinitionResolver->addSchemaDefinitionForField($schemaDefinition, $fieldName);
            return;
        }

        $this->addSchemaDefinitionForEnumField($schemaDefinition, $fieldName);
    }

    /**
     * Add the enum values in the schema: arrays of enum name, description, deprecated and deprecation description
     */
    protected function addSchemaDefinitionForEnumField(array &$schemaDefinition, string $fieldName): void
    {
        $fieldTypeResolverClass = $this->getFieldTypeResolverClass($fieldName);
        if (SchemaHelpers::isEnumFieldTypeResolverClass($fieldTypeResolverClass)) {
            /** @var EnumTypeResolverInterface */
            $fieldEnumTypeResolver = $this->instanceManager->getInstance($fieldTypeResolverClass);
            $this->doAddSchemaDefinitionEnumValuesForField(
                $schemaDefinition,
                $fieldEnumTypeResolver->getEnumValues(),
                $fieldEnumTypeResolver->getEnumValueDeprecationMessages(),
                $fieldEnumTypeResolver->getEnumValueDescriptions(),
                $fieldEnumTypeResolver->getMaybeNamespacedTypeName()
            );
        }
    }
}
