<?php

declare(strict_types=1);

namespace PoP\ComponentModel\RelationalTypeResolverDecorators;

use PoP\ComponentModel\AttachableExtensions\AttachableExtensionManagerInterface;
use PoP\ComponentModel\AttachableExtensions\AttachableExtensionTrait;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\BasicService\BasicServiceTrait;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

abstract class AbstractRelationalTypeResolverDecorator implements RelationalTypeResolverDecoratorInterface
{
    use AttachableExtensionTrait;
    use BasicServiceTrait;

    private ?FieldQueryInterpreterInterface $fieldQueryInterpreter = null;
    private ?AttachableExtensionManagerInterface $attachableExtensionManager = null;

    final public function setFieldQueryInterpreter(FieldQueryInterpreterInterface $fieldQueryInterpreter): void
    {
        $this->fieldQueryInterpreter = $fieldQueryInterpreter;
    }
    final protected function getFieldQueryInterpreter(): FieldQueryInterpreterInterface
    {
        return $this->fieldQueryInterpreter ??= $this->instanceManager->getInstance(FieldQueryInterpreterInterface::class);
    }
    final public function setAttachableExtensionManager(AttachableExtensionManagerInterface $attachableExtensionManager): void
    {
        $this->attachableExtensionManager = $attachableExtensionManager;
    }
    final protected function getAttachableExtensionManager(): AttachableExtensionManagerInterface
    {
        return $this->attachableExtensionManager ??= $this->instanceManager->getInstance(AttachableExtensionManagerInterface::class);
    }

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
     */
    public function getMandatoryDirectivesForFields(ObjectTypeResolverInterface $objectTypeResolver): array
    {
        return [];
    }

    /**
     * Return an array of directiveName as keys, and, for each directiveName,
     * an array of directives (including directive arguments) to be applied before
     */
    public function getPrecedingMandatoryDirectivesForDirectives(RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        return [];
    }

    /**
     * Return an array of directiveName as keys, and, for each directiveName,
     * an array of directives (including directive arguments) to be applied after
     */
    public function getSucceedingMandatoryDirectivesForDirectives(RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        return [];
    }
}
