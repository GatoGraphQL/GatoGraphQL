<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentProcessors;

interface FormInputComponentProcessorInterface
{
    public function getValue(array $module, ?array $source = null): mixed;
    public function getDefaultValue(array $module, array &$props): mixed;
    public function getName(array $module): string;
    public function getInputName(array $module): string;
    public function isMultiple(array $module): bool;
    public function isInputSetInSource(array $module, ?array $source = null): mixed;
}
