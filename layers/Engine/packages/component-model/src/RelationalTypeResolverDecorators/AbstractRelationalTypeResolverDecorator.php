<?php

declare(strict_types=1);

namespace PoP\ComponentModel\RelationalTypeResolverDecorators;

use PoP\ComponentModel\AttachableExtensions\AttachableExtensionManagerInterface;
use PoP\ComponentModel\AttachableExtensions\AttachableExtensionTrait;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\Root\Services\BasicServiceTrait;

abstract class AbstractRelationalTypeResolverDecorator implements RelationalTypeResolverDecoratorInterface
{
    use AttachableExtensionTrait;
    use BasicServiceTrait;

    private ?AttachableExtensionManagerInterface $attachableExtensionManager = null;

    final protected function getAttachableExtensionManager(): AttachableExtensionManagerInterface
    {
        if ($this->attachableExtensionManager === null) {
            /** @var AttachableExtensionManagerInterface */
            $attachableExtensionManager = $this->instanceManager->getInstance(AttachableExtensionManagerInterface::class);
            $this->attachableExtensionManager = $attachableExtensionManager;
        }
        return $this->attachableExtensionManager;
    }

    /**
     * @return string[] Either the class, or the constant "*" to represent _any_ class
     */
    final public function getClassesToAttachTo(): array
    {
        return $this->getRelationalTypeResolverClassesToAttachTo();
    }

    /**
     * Allow to disable the functionality
     */
    public function enabled(RelationalTypeResolverInterface $relationalTypeResolver): bool
    {
        return true;
    }

    /**
     * Return an array of fieldNames as keys, and, for each fieldName, an array of directives (including directive arguments) to be applied always on the field
     *
     * @return array<string,Directive[]> Key: fieldName or "*" (for any field), Value: List of Directives
     */
    public function getMandatoryDirectivesForFields(ObjectTypeResolverInterface $objectTypeResolver): array
    {
        return [];
    }

    /**
     * Return an array of directiveName as keys, and, for each directiveName,
     * an array of directives (including directive arguments) to be applied before
     *
     * @return array<string,Directive[]>
     */
    public function getPrecedingMandatoryDirectivesForDirectives(RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        return [];
    }

    /**
     * Return an array of directiveName as keys, and, for each directiveName,
     * an array of directives (including directive arguments) to be applied after
     *
     * @return array<string,Directive[]> Key: directiveName, Value: List of Directives
     */
    public function getSucceedingMandatoryDirectivesForDirectives(RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        return [];
    }
}
