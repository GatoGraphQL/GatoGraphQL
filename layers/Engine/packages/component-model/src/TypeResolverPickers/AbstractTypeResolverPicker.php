<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolverPickers;

use PoP\ComponentModel\AttachableExtensions\AttachableExtensionInterface;
use PoP\ComponentModel\AttachableExtensions\AttachableExtensionTrait;

abstract class AbstractTypeResolverPicker implements TypeResolverPickerInterface, AttachableExtensionInterface
{
    use AttachableExtensionTrait;

    public function isIDOfType($resultItemID): bool
    {
        return true;
    }
}
