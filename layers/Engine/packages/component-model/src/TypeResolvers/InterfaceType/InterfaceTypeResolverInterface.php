<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\InterfaceType;

use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;

interface InterfaceTypeResolverInterface extends TypeResolverInterface
{
    /**
     * The list of the fieldNames to implement in the Interface,
     * collected from all the injected InterfaceTypeFieldResolvers
     *
     * @return string[]
     */
    public function getFieldNamesToImplement(): array;
    /**
     * Produce an array of all the interface's fieldNames and, for each,
     * a list of all the ObjectTypeFieldResolverInterfaces
     *
     * @return array<string, InterfaceTypeFieldResolverInterface[]>
     */
    public function getAllInterfaceTypeFieldResolversByField(): array;
    /**
     * Produce an array of all the attached ObjectTypeFieldResolverInterfaces
     *
     * @return InterfaceTypeFieldResolverInterface[]
     */
    public function getAllInterfaceTypeFieldResolvers(): array;
    /**
     * Interfaces "partially" implemented by this Interface
     *
     * @return InterfaceTypeResolverInterface[]
     */
    public function getPartiallyImplementedInterfaceTypeResolvers(): array;
}
