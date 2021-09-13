<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldInterfaceResolvers;

use PoP\ComponentModel\AttachableExtensions\AttachableExtensionTrait;
use PoP\ComponentModel\Facades\Schema\SchemaDefinitionServiceFacade;
use PoP\ComponentModel\FieldResolvers\AbstractFieldResolver;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Registries\TypeRegistryInterface;
use PoP\ComponentModel\Resolvers\WithVersionConstraintFieldOrDirectiveResolverTrait;
use PoP\ComponentModel\Schema\SchemaNamespacingServiceInterface;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\Engine\CMS\CMSServiceInterface;
use PoP\Hooks\HooksAPIInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Translation\TranslationAPIInterface;

abstract class AbstractInterfaceTypeFieldResolver extends AbstractFieldResolver implements InterfaceTypeFieldResolverInterface, FieldInterfaceSchemaDefinitionResolverInterface
{
    use AttachableExtensionTrait;
    use WithVersionConstraintFieldOrDirectiveResolverTrait;

    /**
     * @var InterfaceTypeResolverInterface[]|null
     */
    protected ?array $partiallyImplementedInterfaceTypeResolvers = null;

    public function __construct(
        protected TranslationAPIInterface $translationAPI,
        protected HooksAPIInterface $hooksAPI,
        protected InstanceManagerInterface $instanceManager,
        protected NameResolverInterface $nameResolver,
        protected CMSServiceInterface $cmsService,
        protected SchemaNamespacingServiceInterface $schemaNamespacingService,
        protected TypeRegistryInterface $typeRegistry,
    ) {
    }

    /**
     * The InterfaceTypes the FieldInterfaceResolver adds fields to
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

    public function getImplementedFieldInterfaceResolverClasses(): array
    {
        return [];
    }

    /**
     * Each FieldInterfaceResolver provides a list of fieldNames to the Interface.
     * The Interface may also accept other fieldNames from other FieldInterfaceResolvers.
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
     * `getFieldInterfaceSchemaDefinitionResolverClass` is
     * implemented
     */
    protected function getSchemaDefinitionResolver(string $fieldName): FieldInterfaceSchemaDefinitionResolverInterface
    {
        if ($fieldInterfaceSchemaDefinitionResolverClass = $this->getFieldInterfaceSchemaDefinitionResolverClass($fieldName)) {
            /** @var FieldInterfaceSchemaDefinitionResolverInterface */
            return $this->instanceManager->getInstance($fieldInterfaceSchemaDefinitionResolverClass);
        }
        return $this;
    }

    /**
     * Retrieve the class of some FieldInterfaceSchemaDefinitionResolverInterface
     * By default, if the InterfaceTypeFieldResolver implements an interface,
     * it is used as SchemaDefinitionResolver for the matching fields
     */
    protected function getFieldInterfaceSchemaDefinitionResolverClass(string $fieldName): ?string
    {
        foreach ($this->getImplementedFieldInterfaceResolverClasses() as $implementedFieldInterfaceResolverClass) {
            /** @var InterfaceTypeFieldResolverInterface */
            $implementedFieldInterfaceResolver = $this->instanceManager->getInstance($implementedFieldInterfaceResolverClass);
            ;
            if (!in_array($fieldName, $implementedFieldInterfaceResolver->getFieldNamesToImplement())) {
                continue;
            }
            return $implementedFieldInterfaceResolverClass;
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

    public function getFieldTypeResolverClass(string $fieldName): ?string
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($fieldName);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getFieldTypeResolverClass($fieldName);
        }
        return null;
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
        }
    }
}
