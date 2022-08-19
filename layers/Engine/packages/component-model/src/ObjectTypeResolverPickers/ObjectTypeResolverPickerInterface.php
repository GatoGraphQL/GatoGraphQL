<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ObjectTypeResolverPickers;

use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
use PoP\ComponentModel\AttachableExtensions\AttachableExtensionInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

interface ObjectTypeResolverPickerInterface extends AttachableExtensionInterface
{
    /**
     * The classes of the UnionTypeResolvers this TypeResolverPicker will be added to.
     * The list can contain both concrete and abstract classes (in which case all classes
     * extending from them will be selected)
     *
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array;
    public function getObjectTypeResolver(): ObjectTypeResolverInterface;
    public function isIDOfType(string|int $objectID): bool;
    public function isInstanceOfType(object $object): bool;
}
