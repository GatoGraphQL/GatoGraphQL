<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\Interface;

use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;

interface InterfaceTypeResolverInterface extends TypeResolverInterface
{
    /**
     * Produce an array of all the interface's fieldNames and, for each,
     * a list of all the FieldResolverInterfaces
     * 
     * @return array<string, FieldInterfaceResolverInterface[]>
     */
    public function getAllFieldInterfaceResolversByField(): array;
    /**
     * Produce an array of all the interface's fieldNames and, for each,
     * a list of all the FieldResolverInterface classes
     * 
     * @return array<string, string[]>
     */
    public function getAllFieldInterfaceResolverClassesByField(): array;
    /**
     * Produce an array of all the attached FieldResolverInterfaces
     * 
     * @return FieldInterfaceResolverInterface[]
     */
    public function getAllFieldInterfaceResolvers(): array;
    /**
     * Produce an array of all the attached FieldResolverInterface classes
     * 
     * @return string[]
     */
    public function getAllFieldInterfaceResolverClasses(): array;
}
