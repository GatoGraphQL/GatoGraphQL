<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentProcessors;

interface FilterInputContainerComponentProcessorInterface extends FilterDataComponentProcessorInterface
{
    public function getFieldFilterInputNameTypeResolvers(\PoP\ComponentModel\Component\Component $component): array;
    public function getFieldFilterInputDescription(\PoP\ComponentModel\Component\Component $component, string $fieldArgName): ?string;
    public function getFieldFilterInputDefaultValue(\PoP\ComponentModel\Component\Component $component, string $fieldArgName): mixed;
    public function getFieldFilterInputTypeModifiers(\PoP\ComponentModel\Component\Component $component, string $fieldArgName): int;
    public function getFilterInputComponents(\PoP\ComponentModel\Component\Component $component): array;
}
