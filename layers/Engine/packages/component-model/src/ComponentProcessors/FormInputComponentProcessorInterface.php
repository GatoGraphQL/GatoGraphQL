<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentProcessors;

interface FormInputComponentProcessorInterface
{
    public function getValue(array $componentVariation, ?array $source = null): mixed;
    public function getDefaultValue(array $componentVariation, array &$props): mixed;
    public function getName(array $componentVariation): string;
    public function getInputName(array $componentVariation): string;
    public function isMultiple(array $componentVariation): bool;
    public function isInputSetInSource(array $componentVariation, ?array $source = null): mixed;
}
