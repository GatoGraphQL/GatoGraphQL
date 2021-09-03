<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolverPickers;

use PoP\ComponentModel\AttachableExtensions\AttachableExtensionTrait;

abstract class AbstractObjectTypeResolverPicker implements ObjectTypeResolverPickerInterface
{
    use AttachableExtensionTrait;

    public function isIDOfType(string | int $resultItemID): bool
    {
        return true;
    }
}
