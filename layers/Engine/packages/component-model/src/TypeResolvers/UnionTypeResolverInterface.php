<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\TypeResolverPickers\ObjectTypeResolverPickerInterface;

interface UnionTypeResolverInterface extends RelationalTypeResolverInterface
{
    // public function addTypeToID(string | int $resultItemID): string;
    public function getTypeResolverClassForResultItem(string | int $resultItemID);
    public function getTargetTypeResolverPicker(object $resultItem): ?ObjectTypeResolverPickerInterface;
    public function getTargetObjectTypeResolver(object $resultItem): ?RelationalTypeResolverInterface;
    /**
     * @param array<string|int> $ids
     */
    public function getResultItemIDTargetObjectTypeResolvers(array $ids): array;
    public function getTargetObjectTypeResolverClasses(): array;
    public function getSchemaTypeInterfaceClass(): ?string;
    /**
     * @return ObjectTypeResolverPickerInterface[]
     */
    public function getObjectTypeResolverPickers(): array;
}
