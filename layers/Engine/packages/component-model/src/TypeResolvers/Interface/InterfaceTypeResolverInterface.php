<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\Interface;

use PoP\ComponentModel\FieldInterfaceResolvers\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;

interface InterfaceTypeResolverInterface extends TypeResolverInterface
{
    /**
     * The list of the fieldNames to implement in the Interface,
     * collected from all the injected FieldInterfaceResolvers
     *
     * @return string[]
     */
    public function getFieldNamesToImplement(): array;
    /**
     * Produce an array of all the interface's fieldNames and, for each,
     * a list of all the FieldResolverInterfaces
     *
     * @return array<string, InterfaceTypeFieldResolverInterface[]>
     */
    public function getAllFieldInterfaceResolversByField(): array;
    /**
     * Produce an array of all the interface's fieldNames and, for each,
     * a list of all the ObjectTypeFieldResolverInterface classes
     *
     * @return array<string, string[]>
     */
    public function getAllFieldInterfaceResolverClassesByField(): array;
    /**
     * Produce an array of all the attached FieldResolverInterfaces
     *
     * @return InterfaceTypeFieldResolverInterface[]
     */
    public function getAllFieldInterfaceResolvers(): array;
    /**
     * Produce an array of all the attached ObjectTypeFieldResolverInterface classes
     *
     * @return string[]
     */
    public function getAllFieldInterfaceResolverClasses(): array;
    /**
     * Interfaces "partially" implemented by this Interface
     *
     * @return string[]
     */
    public function getPartiallyImplementedInterfaceTypeResolverClasses(): array;
    /**
     * Interfaces "partially" implemented by this Interface
     *
     * @return InterfaceTypeResolverInterface[]
     */
    public function getPartiallyImplementedInterfaceTypeResolvers(): array;
}
