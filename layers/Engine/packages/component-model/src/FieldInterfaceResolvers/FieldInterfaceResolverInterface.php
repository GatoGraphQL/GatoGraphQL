<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldInterfaceResolvers;

use PoP\ComponentModel\FieldInterfaceResolvers\FieldInterfaceSchemaDefinitionResolverInterface;

interface FieldInterfaceResolverInterface extends FieldInterfaceSchemaDefinitionResolverInterface
{
    /**
     * Get an array with the fieldNames that the fieldResolver must implement
     *
     * @return array
     */
    public function getFieldNamesToImplement(): array;
    /**
     * An interface can itself implement other interfaces!
     *
     * @return array
     */
    public function getImplementedFieldInterfaceResolverClasses(): array;
    public function getInterfaceName(): string;
    public function getNamespace(): string;
    public function getNamespacedInterfaceName(): string;
    public function getMaybeNamespacedInterfaceName(): string;
    public function getSchemaInterfaceDescription(): ?string;
    // public function getSchemaInterfaceVersion(string $fieldName): ?string;
    /**
     * This function is not called by the engine, to generate the schema.
     * Instead, the resolver is obtained from the fieldResolver.
     * To make sure that all fieldResolvers implementing the same interface
     * return the expected type for the field, they can obtain it from the
     * interface through this function.
     */
    public function getFieldTypeResolverClass(string $fieldName): ?string;
}
