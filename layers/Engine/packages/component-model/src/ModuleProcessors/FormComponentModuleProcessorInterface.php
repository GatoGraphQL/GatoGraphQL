<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModuleProcessors;

interface FormComponentModuleProcessorInterface
{
    public function getValue(array $module, ?array $source = null);
    public function getDefaultValue(array $module, array &$props);
    public function getName(array $module);
    public function getInputName(array $module);
    public function isMultiple(array $module);
}
