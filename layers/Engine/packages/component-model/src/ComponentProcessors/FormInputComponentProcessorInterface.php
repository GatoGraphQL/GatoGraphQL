<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentProcessors;

interface FormInputComponentProcessorInterface
{
    public function getValue(\PoP\ComponentModel\Component\Component $component, ?array $source = null): mixed;
    public function getDefaultValue(\PoP\ComponentModel\Component\Component $component, array &$props): mixed;
    public function getName(\PoP\ComponentModel\Component\Component $component): string;
    public function getInputName(\PoP\ComponentModel\Component\Component $component): string;
    public function isMultiple(\PoP\ComponentModel\Component\Component $component): bool;
    public function isInputSetInSource(\PoP\ComponentModel\Component\Component $component, ?array $source = null): mixed;
}
