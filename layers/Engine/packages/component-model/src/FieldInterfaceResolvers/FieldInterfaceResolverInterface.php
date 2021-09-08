<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldInterfaceResolvers;

use PoP\ComponentModel\AttachableExtensions\AttachableExtensionInterface;

interface FieldInterfaceResolverInterface extends AttachableExtensionInterface
{
    /**
     * Get an array with the fieldNames that the fieldResolver must implement
     */
    public function getFieldNamesToImplement(): array;
    /**
     * An interface can itself implement other interfaces!
     */
    public function getImplementedFieldInterfaceResolverClasses(): array;
}
