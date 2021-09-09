<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolverDecorators;

use PoP\ComponentModel\AttachableExtensions\AttachableExtensionInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

interface TypeResolverDecoratorInterface extends AttachableExtensionInterface
{
    /**
     * The classes of the ObjectTypeResolvers this TypeResolverDecorator decorates.
     * The list can contain both concrete and abstract classes (in which case all classes
     * extending from them will be selected)
     * 
     * @return string[]
     */
    public function getObjectTypeResolverClassesToAttachTo(): array;
    /**
     * Allow to disable the functionality
     */
    public function enabled(RelationalTypeResolverInterface $relationalTypeResolver): bool;
    /**
     * Return an array of fieldNames as keys, and, for each fieldName, an array of directives (including directive arguments) to be applied always on the field
     */
    public function getMandatoryDirectivesForFields(RelationalTypeResolverInterface $relationalTypeResolver): array;
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
