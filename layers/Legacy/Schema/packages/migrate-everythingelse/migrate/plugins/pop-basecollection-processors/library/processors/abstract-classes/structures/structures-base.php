<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_StructuresBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getInnerSubmodule(array $componentVariation)
    {
        return null;
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);

        // Sometimes there's no inner. Eg: self::MODULE_CONTENT_ADDCONTENTFAQ
        if ($inner = $this->getInnerSubmodule($componentVariation)) {
            $ret[] = $inner;
        }

        return $ret;
    }

    public function addFetchedData(array $componentVariation, array &$props)
    {
        return true;
    }

    public function addWebPlatformModuleConfiguration(&$ret, array $componentVariation, array &$props)
    {
        if ($inner = $this->getInnerSubmodule($componentVariation)) {
            // Add 'pop-merge' + inside module classes, so the processBlock knows where to insert the new html code
            if ($this->addFetchedData($componentVariation, $props)) {
                $ret['class-merge'] = PoP_WebPlatformEngine_Module_Utils::getMergeClass($inner);
            }
        }
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        if ($inner = $this->getInnerSubmodule($componentVariation)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['inner'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($inner);
        }

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {

        // // Make the inner module callback updatable
        // if ($this->addFetchedData($componentVariation, $props)) {

        //     if ($inner = $this->getInnerSubmodule($componentVariation)) {
        //         $this->setProp($inner, $props, 'module-cb', true);
        //     }
        // }

        // Artificial property added to identify the module when adding module-resources
        $this->setProp($componentVariation, $props, 'resourceloader', 'structure');

        parent::initModelProps($componentVariation, $props);
    }
}
