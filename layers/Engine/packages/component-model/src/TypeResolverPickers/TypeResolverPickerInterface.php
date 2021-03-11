<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolverPickers;

use PoP\ComponentModel\AttachableExtensions\AttachableExtensionInterface;

interface TypeResolverPickerInterface extends AttachableExtensionInterface
{
    public function getTypeResolverClass(): string;
    public function isIDOfType($resultItemID): bool;
    public function isInstanceOfType($object): bool;
}
