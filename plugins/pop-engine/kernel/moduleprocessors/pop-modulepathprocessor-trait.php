<?php
namespace PoP\Engine;

trait ModulePathProcessorTrait
{

    //-------------------------------------------------
    // PUBLIC Functions
    //-------------------------------------------------
    public function getSettingsId($module)
    {
        return $module;
    }

    public function getDescendantModules($module)
    {
        return array();
    }

    //-------------------------------------------------
    // PROTECTED Functions
    //-------------------------------------------------

    protected function getModuleProcessor($module)
    {
        $moduleprocessor_manager = ModuleProcessor_Manager_Factory::getInstance();
        return $moduleprocessor_manager->getProcessor($module);
    }

    //-------------------------------------------------
    // TEMPLATE Functions
    //-------------------------------------------------

    protected function hasNoDataloader($module)
    {
        return is_null($this->getModuleProcessor($module)->getDataloader($module));
    }

    protected function hasDataloader($module)
    {
        return !$this->hasNoDataloader($module);
    }

    // $use_settings_id_as_key: For response structures (eg: configuration, feedback, etc) must be true
    // for internal structures (eg: $props, $data_properties) no need
    protected function executeOnSelfAndPropagateToDatasetmodules($eval_self_fn, $propagate_fn, $module, &$props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids)
    {
        $ret = array();
        $key = $this->getSettingsId($module);

        // If modulepaths is provided, and we haven't reached the destination module yet, then do not execute the function at this level
        $modulefilter_manager = ModuleFilterManager_Factory::getInstance();
        if (!$modulefilter_manager->excludeModule($module, $props)) {
            if ($module_ret = $this->$eval_self_fn($module, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids)) {
                $ret[$key] = $module_ret;
            }
        }
                
        // Stop iterating when the submodule starts a new cycle of loading data (i.e. if it has a dataloader)
        $submodules = array_filter($this->getDescendantModules($module), array($this, 'hasNoDataloader'));
        $submodules = $modulefilter_manager->removeExcludedSubmodules($module, $submodules);

        // This function must be called always, to register matching modules into requestmeta.filtermodules even when the module has no submodules
        $module_path_manager = ModulePathManager_Factory::getInstance();
        $module_path_manager->prepareForPropagation($module);
        $submodules_ret = array();
        foreach ($submodules as $submodule) {
            $submodules_ret = array_merge(
                $submodules_ret,
                $this->getModuleProcessor($submodule)->$propagate_fn($submodule, $props[$module][POP_PROPS_MODULES], $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids)
            );
        }
        if ($submodules_ret) {
            $ret[$key][GD_JS_MODULES] = $submodules_ret;
        }
        $module_path_manager->restoreFromPropagation($module);
        
        return $ret;
    }

    protected function executeOnSelfAndMergeWithDatasetmodules($eval_self_fn, $propagate_fn, $module, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids)
    {

        // If modulepaths is provided, and we haven't reached the destination module yet, then do not execute the function at this level
        $modulefilter_manager = ModuleFilterManager_Factory::getInstance();
        if (!$modulefilter_manager->excludeModule($module, $props)) {
            $ret = $this->$eval_self_fn($module, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids);
        } else {
            $ret = array();
        }
        
        // Stop iterating when the submodule starts a new cycle of loading data (i.e. if it has a dataloader)
        $submodules = array_filter($this->getDescendantModules($module), array($this, 'hasNoDataloader'));
        $submodules = $modulefilter_manager->removeExcludedSubmodules($module, $submodules);

        // This function must be called always, to register matching modules into requestmeta.filtermodules even when the module has no submodules
        $module_path_manager = ModulePathManager_Factory::getInstance();
        $module_path_manager->prepareForPropagation($module);
        foreach ($submodules as $submodule) {
            $ret = array_merge_recursive(
                $ret,
                $this->getModuleProcessor($submodule)->$propagate_fn($submodule, $props[$module][POP_PROPS_MODULES], $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids)
            );
        }
        $module_path_manager->restoreFromPropagation($module);
        
        return $ret;
    }

    // $use_settings_id_as_key: For response structures (eg: configuration, feedback, etc) must be true
    // for internal structures (eg: $props, $data_properties) no need
    protected function executeOnSelfAndPropagateToModules($eval_self_fn, $propagate_fn, $module, &$props, $use_settings_id_as_key = true, $options = array())
    {
        $ret = array();
        $key = $use_settings_id_as_key ? $this->getSettingsId($module) : $module;
        
        // If modulepaths is provided, and we haven't reached the destination module yet, then do not execute the function at this level
        $modulefilter_manager = ModuleFilterManager_Factory::getInstance();
        if (!$modulefilter_manager->excludeModule($module, $props)) {
            
            // Maybe only execute function on the dataloading modules
            if (!$options['only-execute-on-dataloading-modules'] || $this->hasDataloader($module)) {
                if ($module_ret = $this->$eval_self_fn($module, $props)) {
                    $ret[$key] = $module_ret;
                }
            }
        }

        $submodules = $this->getDescendantModules($module);
        $submodules = $modulefilter_manager->removeExcludedSubmodules($module, $submodules);

        // This function must be called always, to register matching modules into requestmeta.filtermodules even when the module has no submodules
        $module_path_manager = ModulePathManager_Factory::getInstance();
        $module_path_manager->prepareForPropagation($module);
        $submodules_ret = array();
        foreach ($submodules as $submodule) {
            $submodules_ret = array_merge(
                $submodules_ret,
                $this->getModuleProcessor($submodule)->$propagate_fn($submodule, $props[$module][POP_PROPS_MODULES])
            );
        }
        if ($submodules_ret) {
            $ret[$key][GD_JS_MODULES] = $submodules_ret;
        }
        $module_path_manager->restoreFromPropagation($module);
        
        return $ret;
    }

    protected function executeOnSelfAndMergeWithModules($eval_self_fn, $propagate_fn, $module, &$props, $recursive = true)
    {

        // If modulepaths is provided, and we haven't reached the destination module yet, then do not execute the function at this level
        $modulefilter_manager = ModuleFilterManager_Factory::getInstance();
        if (!$modulefilter_manager->excludeModule($module, $props)) {
            $ret = $this->$eval_self_fn($module, $props);
        } else {
            $ret = array();
        }
        
        $submodules = $this->getDescendantModules($module);
        $submodules = $modulefilter_manager->removeExcludedSubmodules($module, $submodules);

        // This function must be called always, to register matching modules into requestmeta.filtermodules even when the module has no submodules
        $module_path_manager = ModulePathManager_Factory::getInstance();
        $module_path_manager->prepareForPropagation($module);
        foreach ($submodules as $submodule) {
            $submodule_ret = $this->getModuleProcessor($submodule)->$propagate_fn($submodule, $props[$module][POP_PROPS_MODULES], $recursive);
            $ret = $recursive ?
            array_merge_recursive(
                $ret,
                $submodule_ret
            ) :
            array_unique(
                array_values(
                    array_merge(
                        $ret,
                        $submodule_ret
                    )
                )
            );
        }
        $module_path_manager->restoreFromPropagation($module);
        
        return $ret;
    }
}
