<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentProcessors;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;

interface FilterInputComponentProcessorInterface extends FormInputComponentProcessorInterface
{
    public function getFilterInputTypeResolver(\PoP\ComponentModel\Component\Component $component): InputTypeResolverInterface;
    public function getFilterInputDescription(\PoP\ComponentModel\Component\Component $component): ?string;
    public function getFilterInputDefaultValue(\PoP\ComponentModel\Component\Component $component): mixed;
    public function getFilterInputTypeModifiers(\PoP\ComponentModel\Component\Component $component): int;
}
