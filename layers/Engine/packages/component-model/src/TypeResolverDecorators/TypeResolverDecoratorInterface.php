<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolverDecorators;

use PoP\ComponentModel\AttachableExtensions\AttachableExtensionInterface;
use PoP\ComponentModel\TypeResolvers\ObjectTypeResolverInterface;

interface TypeResolverDecoratorInterface extends AttachableExtensionInterface
{
    /**
     * Allow to disable the functionality
     */
    public function enabled(ObjectTypeResolverInterface $typeResolver): bool;
    /**
     * Return an array of fieldNames as keys, and, for each fieldName, an array of directives (including directive arguments) to be applied always on the field
     */
    public function getMandatoryDirectivesForFields(ObjectTypeResolverInterface $typeResolver): array;
    /**
     * Return an array of directiveName as keys, and, for each directiveName,
     * an array of directives (including directive arguments) to be applied before
     */
    public function getPrecedingMandatoryDirectivesForDirectives(ObjectTypeResolverInterface $typeResolver): array;
    /**
     * Return an array of directiveName as keys, and, for each directiveName,
     * an array of directives (including directive arguments) to be applied after
     */
    public function getSucceedingMandatoryDirectivesForDirectives(ObjectTypeResolverInterface $typeResolver): array;
}
