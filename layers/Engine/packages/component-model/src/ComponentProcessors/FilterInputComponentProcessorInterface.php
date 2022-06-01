<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;

interface FilterInputComponentProcessorInterface extends FormInputComponentProcessorInterface
{
    public function getFilterInputTypeResolver(Component $component): InputTypeResolverInterface;
    public function getFilterInputDescription(Component $component): ?string;
    public function getFilterInputDefaultValue(Component $component): mixed;
    public function getFilterInputTypeModifiers(Component $component): int;
}
