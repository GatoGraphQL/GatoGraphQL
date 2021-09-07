<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldInterfaceResolvers;

use PoP\ComponentModel\Facades\Schema\SchemaDefinitionServiceFacade;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Resolvers\WithVersionConstraintFieldOrDirectiveResolverTrait;
use PoP\ComponentModel\Schema\SchemaNamespacingServiceInterface;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Engine\CMS\CMSServiceInterface;
use PoP\Hooks\HooksAPIInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Translation\TranslationAPIInterface;

abstract class AbstractFieldInterfaceResolver implements FieldInterfaceResolverInterface
{
    use WithVersionConstraintFieldOrDirectiveResolverTrait;

    public function __construct(
        protected TranslationAPIInterface $translationAPI,
        protected HooksAPIInterface $hooksAPI,
        protected InstanceManagerInterface $instanceManager,
        protected NameResolverInterface $nameResolver,
        protected CMSServiceInterface $cmsService,
        protected SchemaNamespacingServiceInterface $schemaNamespacingService,
    ) {
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
     * Return the object implementing the schema definition for this FieldInterfaceResolver.
     * By default, it is this same object
     */
    public function getSchemaDefinitionResolver(string $fieldName): FieldInterfaceSchemaDefinitionResolverInterface
    {
        return $this;
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

    public function resolveFieldTypeResolverClass(string $fieldName): ?string
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($fieldName);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->resolveFieldTypeResolverClass($fieldName);
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

    public function getNamespace(): string
    {
        return $this->schemaNamespacingService->getSchemaNamespace(get_called_class());
    }

    final public function getNamespacedInterfaceName(): string
    {
        return $this->schemaNamespacingService->getSchemaNamespacedName(
            $this->getNamespace(),
            $this->getInterfaceName()
        );
    }

    final public function getMaybeNamespacedInterfaceName(): string
    {
        $vars = ApplicationState::getVars();
        return $vars['namespace-types-and-interfaces'] ?
            $this->getNamespacedInterfaceName() :
            $this->getInterfaceName();
    }

    public function getSchemaInterfaceDescription(): ?string
    {
        return null;
    }

    // public function getSchemaInterfaceVersion(string $fieldName): ?string
    // {
    //     return null;
    // }
}
