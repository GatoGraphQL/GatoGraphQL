<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers;

use PoP\ComponentModel\AttachableExtensions\AttachableExtensionInterface;
use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;

interface FieldResolverInterface extends AttachableExtensionInterface
{
    /**
     * Those fieldNames to be enabled for the "Admin" schema only
     *
     * @return string[]
     */
    public function getSensitiveFieldNames(): array;
    /**
     * Each InterfaceTypeFieldResolver provides a list of fieldNames to the Interface.
     * The Interface may also accept other fieldNames from other InterfaceTypeFieldResolvers.
     * That's why this function is "partially" implemented: the Interface
     * may be completely implemented or not.
     *
     * @return InterfaceTypeResolverInterface[]
     */
    public function getPartiallyImplementedInterfaceTypeResolvers(): array;
    /**
     * The interfaces the fieldResolver implements
     *
     * @return InterfaceTypeFieldResolverInterface[]
     */
    public function getImplementedInterfaceTypeFieldResolvers(): array;
}
