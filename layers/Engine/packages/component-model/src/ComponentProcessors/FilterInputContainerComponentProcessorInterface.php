<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentProcessors;

interface FilterInputContainerComponentProcessorInterface extends FilterDataComponentProcessorInterface
{
    public function getFieldFilterInputNameTypeResolvers(array $component): array;
    public function getFieldFilterInputDescription(array $component, string $fieldArgName): ?string;
    public function getFieldFilterInputDefaultValue(array $component, string $fieldArgName): mixed;
    public function getFieldFilterInputTypeModifiers(array $component, string $fieldArgName): int;
    public function getFilterInputComponents(array $component): array;
}
