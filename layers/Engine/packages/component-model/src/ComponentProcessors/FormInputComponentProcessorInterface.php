<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
interface FormInputComponentProcessorInterface
{
    public function getValue(Component $component, ?array $source = null): mixed;
    public function getDefaultValue(Component $component, array &$props): mixed;
    public function getName(Component $component): string;
    public function getInputName(Component $component): string;
    public function isMultiple(Component $component): bool;
    public function isInputSetInSource(Component $component, ?array $source = null): mixed;
}
