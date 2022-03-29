<?php

use PoP\ComponentModel\GraphQLModel\ComponentModelSpec\Ast\RelationalModuleField;

abstract class PoP_Module_Processor_PreloadTargetDataButtonsBase extends PoP_Module_Processor_ButtonsBase
{
    public function getTargetDynamicallyRenderedSubmodules(array $module)
    {
        return array();
    }

    /**
     * @return RelationalModuleField[]
     */
    public function getTargetDynamicallyRenderedSubcomponentSubmodules(array $module)
    {
        return array();
    }

    /**
     * @return RelationalModuleField[]
     */
    public function getDomainSwitchingSubmodules(array $module): array
    {
        $ret = parent::getDomainSwitchingSubmodules($module);

        // We need to load the data needed by the datum, so that when executing `triggerSelect` in function `renderDBObjectLayoutFromURLParam`
        // the data has already been preloaded
        if ($dynamic_modules = $this->getTargetDynamicallyRenderedSubcomponentSubmodules($module)) {
            $ret = array_merge(
                $ret,
                $dynamic_modules
            );
        }

        return $ret;
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        // We need to load the data needed by the datum, so that when executing `triggerSelect` in function `renderDBObjectLayoutFromURLParam`
        // the data has already been preloaded
        if ($dynamic_modules = $this->getTargetDynamicallyRenderedSubmodules($module)) {
            $ret = array_merge(
                $ret,
                $dynamic_modules
            );
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {

        // Mark the layouts as needing dynamic data, so the DB data is sent to the webplatform also when doing SSR
        if (defined('POP_SSR_INITIALIZED')) {
            if ($dynamic_modules = $this->getTargetDynamicallyRenderedSubmodules($module)) {
                foreach ($dynamic_modules as $dynamic_module) {
                    $this->setProp($dynamic_module, $props, 'needs-dynamic-data', true);
                }
            }

            if ($subcomponent_dynamic_templates = $this->getTargetDynamicallyRenderedSubcomponentSubmodules($module)) {
                foreach ($subcomponent_dynamic_templates as $data_field => $modules) {
                    foreach ($modules as $dynamic_module) {
                        $this->setProp($dynamic_module, $props, 'needs-dynamic-data', true);
                    }
                }
            }
        }

        parent::initModelProps($module, $props);
    }
}
