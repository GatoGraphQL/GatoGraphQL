<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentProcessors;

interface FilterInputContainerComponentProcessorInterface extends FilterDataComponentProcessorInterface
{
    public function getFieldFilterInputNameTypeResolvers(array $componentVariation): array;
    public function getFieldFilterInputDescription(array $componentVariation, string $fieldArgName): ?string;
    public function getFieldFilterInputDefaultValue(array $componentVariation, string $fieldArgName): mixed;
    public function getFieldFilterInputTypeModifiers(array $componentVariation, string $fieldArgName): int;
    public function getFilterInputComponentVariations(array $componentVariation): array;
}
