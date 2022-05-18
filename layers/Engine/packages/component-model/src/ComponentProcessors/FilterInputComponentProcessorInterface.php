<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentProcessors;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;

interface FilterInputComponentProcessorInterface extends FormInputComponentProcessorInterface
{
    public function getFilterInputTypeResolver(array $componentVariation): InputTypeResolverInterface;
    public function getFilterInputDescription(array $componentVariation): ?string;
    public function getFilterInputDefaultValue(array $componentVariation): mixed;
    public function getFilterInputTypeModifiers(array $componentVariation): int;
}
