<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ObjectTypeResolverPickers;

use PoP\ComponentModel\AttachableExtensions\AttachableExtensionInterface;

interface ObjectTypeResolverPickerInterface extends AttachableExtensionInterface
{
    /**
     * The classes of the UnionTypeResolvers this TypeResolverPicker will be added to.
     * The list can contain both concrete and abstract classes (in which case all classes
     * extending from them will be selected)
     *
     * @return string[]
     */
    public function getUnionTypeResolverClassesToAttachTo(): array;
    public function getObjectTypeResolverClass(): string;
    public function isIDOfType(string | int $resultItemID): bool;
    public function isInstanceOfType(object $object): bool;
}
