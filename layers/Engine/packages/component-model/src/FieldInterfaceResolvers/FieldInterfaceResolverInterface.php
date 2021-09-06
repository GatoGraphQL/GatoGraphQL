<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldInterfaceResolvers;

use PoP\ComponentModel\FieldInterfaceResolvers\FieldInterfaceSchemaDefinitionResolverInterface;

interface FieldInterfaceResolverInterface extends FieldInterfaceSchemaDefinitionResolverInterface
{
    /**
     * Get an array with the fieldNames that the fieldResolver must implement
     */
    public function getFieldNamesToImplement(): array;
    /**
     * An interface can itself implement other interfaces!
     */
    public function getImplementedFieldInterfaceResolverClasses(): array;
    public function getInterfaceName(): string;
    public function getNamespace(): string;
    public function getNamespacedInterfaceName(): string;
    public function getMaybeNamespacedInterfaceName(): string;
    public function getSchemaInterfaceDescription(): ?string;
    // public function getSchemaInterfaceVersion(string $fieldName): ?string;
}
