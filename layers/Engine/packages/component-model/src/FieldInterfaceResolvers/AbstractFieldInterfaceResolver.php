<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldInterfaceResolvers;

use PoP\ComponentModel\FieldInterfaceResolvers\FieldInterfaceSchemaDefinitionResolverTrait;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Schema\SchemaHelpers;
use PoP\ComponentModel\Schema\SchemaNamespacingServiceInterface;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Engine\CMS\CMSServiceInterface;
use PoP\Hooks\HooksAPIInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Translation\TranslationAPIInterface;

abstract class AbstractFieldInterfaceResolver implements FieldInterfaceResolverInterface
{
    use FieldInterfaceSchemaDefinitionResolverTrait;

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

    /**
     * This function is not called by the engine, to generate the schema.
     * Instead, the resolver is obtained from the fieldResolver.
     * To make sure that all fieldResolvers implementing the same interface
     * return the expected type for the field, they can obtain it from the
     * interface through this function.
     */
    public function getFieldTypeResolverClass(string $fieldName): ?string
    {
        return null;
    }
}
