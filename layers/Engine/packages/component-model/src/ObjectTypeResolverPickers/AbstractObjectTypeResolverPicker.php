<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ObjectTypeResolverPickers;

use PoP\ComponentModel\AttachableExtensions\AttachableExtensionTrait;

abstract class AbstractObjectTypeResolverPicker implements ObjectTypeResolverPickerInterface
{
    use AttachableExtensionTrait;

    final public function getClassesToAttachTo(): array
    {
        return $this->getUnionTypeResolverClassesToAttachTo();
    }

    public function isIDOfType(string | int $resultItemID): bool
    {
        return true;
    }
}
