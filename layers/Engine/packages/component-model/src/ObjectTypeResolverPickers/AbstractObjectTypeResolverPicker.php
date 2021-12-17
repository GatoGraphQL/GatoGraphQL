<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ObjectTypeResolverPickers;

use PoP\ComponentModel\AttachableExtensions\AttachableExtensionManagerInterface;
use PoP\ComponentModel\AttachableExtensions\AttachableExtensionTrait;
use PoP\BasicService\BasicServiceTrait;

abstract class AbstractObjectTypeResolverPicker implements ObjectTypeResolverPickerInterface
{
    use AttachableExtensionTrait;
    use BasicServiceTrait;

    private ?AttachableExtensionManagerInterface $attachableExtensionManager = null;

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
        return $this->getUnionTypeResolverClassesToAttachTo();
    }

    public function isIDOfType(string | int $objectID): bool
    {
        return true;
    }
}
