<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentProcessors;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;

interface FilterInputComponentProcessorInterface extends FormInputComponentProcessorInterface
{
    public function getFilterInputTypeResolver(array $component): InputTypeResolverInterface;
    public function getFilterInputDescription(array $component): ?string;
    public function getFilterInputDefaultValue(array $component): mixed;
    public function getFilterInputTypeModifiers(array $component): int;
}
