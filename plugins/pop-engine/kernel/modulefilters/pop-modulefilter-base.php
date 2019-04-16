<?php
namespace PoP\Engine;

abstract class ModuleFilterBase
{
    public function __construct()
    {
        ModuleFilterManager_Factory::getInstance()->add($this);
    }

    abstract public function getName();

    public function excludeModule($module, &$props)
    {
        return false;
    }

    public function removeExcludedSubmodules($module, $submodules)
    {
        return $submodules;
    }

    public function prepareForPropagation($module, &$props)
    {
    }

    public function restoreFromPropagation($module, &$props)
    {
    }
}
