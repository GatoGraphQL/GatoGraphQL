<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_StructuresBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getInnerSubmodule(array $component)
    {
        return null;
    }

    public function getSubcomponents(array $component): array
    {
        $ret = parent::getSubcomponents($component);

        // Sometimes there's no inner. Eg: self::COMPONENT_CONTENT_ADDCONTENTFAQ
        if ($inner = $this->getInnerSubmodule($component)) {
            $ret[] = $inner;
        }

        return $ret;
    }

    public function addFetchedData(array $component, array &$props)
    {
        return true;
    }

    public function addWebPlatformModuleConfiguration(&$ret, array $component, array &$props)
    {
        if ($inner = $this->getInnerSubmodule($component)) {
            // Add 'pop-merge' + inside module classes, so the processBlock knows where to insert the new html code
            if ($this->addFetchedData($component, $props)) {
                $ret['class-merge'] = PoP_WebPlatformEngine_Module_Utils::getMergeClass($inner);
            }
        }
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $ret = parent::getImmutableConfiguration($component, $props);

        if ($inner = $this->getInnerSubmodule($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['inner'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($inner);
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {

        // // Make the inner module callback updatable
        // if ($this->addFetchedData($component, $props)) {

        //     if ($inner = $this->getInnerSubmodule($component)) {
        //         $this->setProp($inner, $props, 'component-cb', true);
        //     }
        // }

        // Artificial property added to identify the module when adding component-resources
        $this->setProp($component, $props, 'resourceloader', 'structure');

        parent::initModelProps($component, $props);
    }
}
