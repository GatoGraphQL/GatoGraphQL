<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolverDecorators;

use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\AttachableExtensions\AttachableExtensionTrait;
use PoP\ComponentModel\TypeResolverDecorators\TypeResolverDecoratorInterface;

abstract class AbstractTypeResolverDecorator implements TypeResolverDecoratorInterface
{
    /**
     * This class is attached to a TypeResolver
     */
    use AttachableExtensionTrait;

    function __construct(
        protected InstanceManagerInterface $instanceManager,
        protected FieldQueryInterpreterInterface $fieldQueryInterpreter,
    ) {
    }

    /**
     * Allow to disable the functionality
     */
    public function enabled(TypeResolverInterface $typeResolver): bool
    {
        return true;
    }

    /**
     * Return an array of fieldNames as keys, and, for each fieldName, an array of directives (including directive arguments) to be applied always on the field
     */
    public function getMandatoryDirectivesForFields(TypeResolverInterface $typeResolver): array
    {
        return [];
    }

    /**
     * Return an array of directiveName as keys, and, for each directiveName,
     * an array of directives (including directive arguments) to be applied before
     */
    public function getPrecedingMandatoryDirectivesForDirectives(TypeResolverInterface $typeResolver): array
    {
        return [];
    }

    /**
     * Return an array of directiveName as keys, and, for each directiveName,
     * an array of directives (including directive arguments) to be applied after
     */
    public function getSucceedingMandatoryDirectivesForDirectives(TypeResolverInterface $typeResolver): array
    {
        return [];
    }
}
