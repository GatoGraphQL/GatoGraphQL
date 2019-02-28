<?php
namespace PoP\Engine;

class ModulePathManager_Utils
{
    public static function getStringifiedModulePropagationCurrentPath($module)
    {
        $module_path_manager = ModulePathManager_Factory::getInstance();
        $module_propagation_current_path = $module_path_manager->getPropagationCurrentPath();
        $module_propagation_current_path[] = $module;
        return self::stringifyModulePath($module_propagation_current_path);
    }

    public static function stringifyModulePath($modulepath)
    {
        return implode(POP_CONSTANT_MODULESTARTPATH_SEPARATOR, $modulepath);
    }

    public static function recastModulePath($modulepath_as_string)
    {
        return explode(POP_CONSTANT_MODULESTARTPATH_SEPARATOR, $modulepath_as_string);
    }
}
