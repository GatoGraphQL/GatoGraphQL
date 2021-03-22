<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers;

use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\TypeResolverPickers\TypeResolverPickerInterface;

interface UnionTypeResolverInterface
{
    // public function addTypeToID(mixed $resultItemID): string;
    public function getTypeResolverClassForResultItem(mixed $resultItemID);
    public function getTargetTypeResolverPicker(object $resultItem): ?TypeResolverPickerInterface;
    public function getTargetTypeResolver(object $resultItem): ?TypeResolverInterface;
    public function getResultItemIDTargetTypeResolvers(array $ids): array;
    public function getTargetTypeResolverClasses(): array;
    public function getSchemaTypeInterfaceClass(): ?string;
    public function getTypeResolverPickers(): array;
}
