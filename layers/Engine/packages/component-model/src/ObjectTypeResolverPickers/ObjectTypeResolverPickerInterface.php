<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ObjectTypeResolverPickers;

use PoP\ComponentModel\AttachableExtensions\AttachableExtensionInterface;

interface ObjectTypeResolverPickerInterface extends AttachableExtensionInterface
{
    public function getObjectTypeResolverClass(): string;
    public function isIDOfType(string | int $resultItemID): bool;
    public function isInstanceOfType(object $object): bool;
}
