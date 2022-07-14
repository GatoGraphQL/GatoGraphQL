<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\UnionType;

use PoP\ComponentModel\ObjectTypeResolverPickers\ObjectTypeResolverPickerInterface;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

interface UnionTypeResolverInterface extends RelationalTypeResolverInterface
{
    // public function addTypeToID(string|int $objectID): string;
    public function getObjectTypeResolverForObject(string|int $objectID): ?ObjectTypeResolverInterface;
    public function getTargetObjectTypeResolverPicker(object $object): ?ObjectTypeResolverPickerInterface;
    public function getTargetObjectTypeResolver(object $object): ?ObjectTypeResolverInterface;
    /**
     * @param array<string|int> $ids
     * @return array<string|int,ObjectTypeResolverInterface|null>
     */
    public function getObjectIDTargetTypeResolvers(array $ids): array;
    /**
     * @return ObjectTypeResolverInterface[]
     */
    public function getTargetObjectTypeResolvers(): array;
    /**
     * @return array<string,ObjectTypeResolverInterface> Key: TypeOutputKey, Value: ObjectTypeResolver
     */
    public function getTargetTypeOutputKeyObjectTypeResolvers(): array;
    /**
     * @return InterfaceTypeResolverInterface[]
     */
    public function getUnionTypeInterfaceTypeResolvers(): array;
    /**
     * @return ObjectTypeResolverPickerInterface[]
     */
    public function getObjectTypeResolverPickers(): array;
}
