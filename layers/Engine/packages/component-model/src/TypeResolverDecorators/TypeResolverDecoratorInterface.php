<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolverDecorators;

use PoP\ComponentModel\AttachableExtensions\AttachableExtensionInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

interface TypeResolverDecoratorInterface extends AttachableExtensionInterface
{
    /**
     * The classes of the RelationalTypeResolvers this TypeResolverDecorator decorates.
     *
     * It is RelationalType and not ObjectType because directives can be applied on
     * the UnionTypeResolver too, and the TypeResolverDecorator will deal with
     * the IFTTT rules for those directives.
     *
     * The list can contain both concrete and abstract classes (in which case all classes
     * extending from them will be selected)
     *
     * @return string[]
     */
    public function getRelationalTypeResolverClassesToAttachTo(): array;
    /**
     * Allow to disable the functionality
     */
    public function enabled(RelationalTypeResolverInterface $relationalTypeResolver): bool;
    /**
     * Return an array of fieldNames as keys, and, for each fieldName, an array of directives (including directive arguments) to be applied always on the field
     */
    public function getMandatoryDirectivesForFields(ObjectTypeResolverInterface $objectTypeResolver): array;
    /**
     * Return an array of directiveName as keys, and, for each directiveName,
     * an array of directives (including directive arguments) to be applied before
     */
    public function getPrecedingMandatoryDirectivesForDirectives(RelationalTypeResolverInterface $relationalTypeResolver): array;
    /**
     * Return an array of directiveName as keys, and, for each directiveName,
     * an array of directives (including directive arguments) to be applied after
     */
    public function getSucceedingMandatoryDirectivesForDirectives(RelationalTypeResolverInterface $relationalTypeResolver): array;
}
