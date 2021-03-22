<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldInterfaceResolvers;

use PoP\ComponentModel\Schema\SchemaHelpers;
use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\FieldInterfaceResolvers\FieldInterfaceSchemaDefinitionResolverTrait;

abstract class AbstractFieldInterfaceResolver implements FieldInterfaceResolverInterface
{
    use FieldInterfaceSchemaDefinitionResolverTrait;

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
        return SchemaHelpers::getSchemaNamespace(get_called_class());
    }

    final public function getNamespacedInterfaceName(): string
    {
        return SchemaHelpers::getSchemaNamespacedName(
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

    /**
     * The fieldResolver will determine if it has a version or not, however the signature
     * of the fields comes from the interface. Only if there's a version will fieldArg "versionConstraint"
     * be added to the field. Hence, the interface must always say it has a version.
     * This will make fieldArg "versionConstraint" be always added to fields implementing an interface,
     * even if they do not have a version. However, the other way around, to say `false`,
     * would not allow any field implementing an interface to be versioned. So this way is better.
     */
    protected function hasSchemaFieldVersion(string $fieldName): bool
    {
        return true;
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
