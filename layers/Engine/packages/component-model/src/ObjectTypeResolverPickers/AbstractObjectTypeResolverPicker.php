<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ObjectTypeResolverPickers;

use PoP\ComponentModel\AttachableExtensions\AttachableExtensionManagerInterface;
use PoP\ComponentModel\AttachableExtensions\AttachableExtensionTrait;
use PoP\Root\Services\BasicServiceTrait;

abstract class AbstractObjectTypeResolverPicker implements ObjectTypeResolverPickerInterface
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
     * @return string[]
     */
    final public function getClassesToAttachTo(): array
    {
        return $this->getUnionTypeResolverClassesToAttachTo();
    }
}
