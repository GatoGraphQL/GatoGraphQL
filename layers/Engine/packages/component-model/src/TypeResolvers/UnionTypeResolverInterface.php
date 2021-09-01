<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers;

use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\TypeResolverPickers\TypeResolverPickerInterface;

interface UnionTypeResolverInterface extends TypeResolverInterface
{
    // public function addTypeToID(string | int $resultItemID): string;
    public function getTypeResolverClassForResultItem(string | int $resultItemID);
    public function getTargetTypeResolverPicker(object $resultItem): ?TypeResolverPickerInterface;
    public function getTargetTypeResolver(object $resultItem): ?TypeResolverInterface;
    /**
     * @param array<string|int> $ids
     */
    public function getResultItemIDTargetTypeResolvers(array $ids): array;
    public function getTargetTypeResolverClasses(): array;
    public function getSchemaTypeInterfaceClass(): ?string;
    public function getTypeResolverPickers(): array;
}
