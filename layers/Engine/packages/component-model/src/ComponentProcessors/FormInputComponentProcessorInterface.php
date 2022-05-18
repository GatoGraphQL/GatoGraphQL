<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentProcessors;

interface FormInputComponentProcessorInterface
{
    public function getValue(array $component, ?array $source = null): mixed;
    public function getDefaultValue(array $component, array &$props): mixed;
    public function getName(array $component): string;
    public function getInputName(array $component): string;
    public function isMultiple(array $component): bool;
    public function isInputSetInSource(array $component, ?array $source = null): mixed;
}
