<?php
namespace PoP\ComponentModel;
interface FormComponent
{
    public function getValue(array $module, ?array $source = null);
    public function getDefaultValue(array $module, array &$props);
    public function getName(array $module);
    public function getInputName(array $module);
    public function isMultiple(array $module);
}
