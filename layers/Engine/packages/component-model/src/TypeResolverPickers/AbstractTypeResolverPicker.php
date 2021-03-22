<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolverPickers;

use PoP\ComponentModel\AttachableExtensions\AttachableExtensionTrait;

abstract class AbstractTypeResolverPicker implements TypeResolverPickerInterface
{
    use AttachableExtensionTrait;

    public function isIDOfType(mixed $resultItemID): bool
    {
        return true;
    }
}
