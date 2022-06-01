<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentProcessors;

use PoP\ComponentModel\Component\Component;

interface FilterInputContainerComponentProcessorInterface extends FilterDataComponentProcessorInterface
{
    public function getFieldFilterInputNameTypeResolvers(Component $component): array;
    public function getFieldFilterInputDescription(Component $component, string $fieldArgName): ?string;
    public function getFieldFilterInputDefaultValue(Component $component, string $fieldArgName): mixed;
    public function getFieldFilterInputTypeModifiers(Component $component, string $fieldArgName): int;
    /**
     * @return Component[]
     */
    public function getFilterInputComponents(Component $component): array;
}
