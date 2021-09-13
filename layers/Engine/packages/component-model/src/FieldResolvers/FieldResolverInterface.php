<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers;

use PoP\ComponentModel\AttachableExtensions\AttachableExtensionInterface;

interface FieldResolverInterface extends AttachableExtensionInterface
{
    /**
     * Those fieldNames to be enabled for the "Admin" schema only
     *
     * @return string[]
     */
    public function getAdminFieldNames(): array;
    /**
     * Each InterfaceTypeFieldResolver provides a list of fieldNames to the Interface.
     * The Interface may also accept other fieldNames from other InterfaceTypeFieldResolvers.
     * That's why this function is "partially" implemented: the Interface
     * may be completely implemented or not.
     *
     * @return string[]
     */
    public function getPartiallyImplementedInterfaceTypeResolverClasses(): array;
    /**
     * A list of classes of all the interfaces the fieldResolver implements
     *
     * @return string[]
     */
    public function getImplementedInterfaceTypeFieldResolverClasses(): array;
}
