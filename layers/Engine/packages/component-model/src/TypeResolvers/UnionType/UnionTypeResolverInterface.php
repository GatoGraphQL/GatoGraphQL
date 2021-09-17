<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\UnionType;

use PoP\ComponentModel\ObjectTypeResolverPickers\ObjectTypeResolverPickerInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

interface UnionTypeResolverInterface extends RelationalTypeResolverInterface
{
    // public function addTypeToID(string | int $objectID): string;
    public function getObjectTypeResolverForObject(string | int $objectID): ?ObjectTypeResolverInterface;
    public function getTargetObjectTypeResolverPicker(object $object): ?ObjectTypeResolverPickerInterface;
    public function getTargetObjectTypeResolver(object $object): ?ObjectTypeResolverInterface;
    /**
     * @param array<string|int> $ids
     */
    public function getObjectIDTargetTypeResolvers(array $ids): array;
    /**
     * @return ObjectTypeResolverInterface[]
     */
    public function getTargetObjectTypeResolvers(): array;
    public function getSchemaTypeInterfaceTypeResolverClass(): ?string;
    /**
     * @return ObjectTypeResolverPickerInterface[]
     */
    public function getObjectTypeResolverPickers(): array;
}
