<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_StructuresBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getInnerSubmodule(array $module)
    {
        return null;
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        // Sometimes there's no inner. Eg: self::MODULE_CONTENT_ADDCONTENTFAQ
        if ($inner = $this->getInnerSubmodule($module)) {
            $ret[] = $inner;
        }

        return $ret;
    }

    public function addFetchedData(array $module, array &$props)
    {
        return true;
    }

    public function addWebPlatformModuleConfiguration(&$ret, array $module, array &$props)
    {
        if ($inner = $this->getInnerSubmodule($module)) {
            // Add 'pop-merge' + inside module classes, so the processBlock knows where to insert the new html code
            if ($this->addFetchedData($module, $props)) {
                $ret['class-merge'] = PoP_WebPlatformEngine_Module_Utils::getMergeClass($inner);
            }
        }
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $ret = parent::getImmutableConfiguration($module, $props);

        if ($inner = $this->getInnerSubmodule($module)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['inner'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($inner);
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {

        // // Make the inner module callback updatable
        // if ($this->addFetchedData($module, $props)) {

        //     if ($inner = $this->getInnerSubmodule($module)) {
        //         $this->setProp($inner, $props, 'module-cb', true);
        //     }
        // }

        // Artificial property added to identify the module when adding module-resources
        $this->setProp($module, $props, 'resourceloader', 'structure');

        parent::initModelProps($module, $props);
    }
}
