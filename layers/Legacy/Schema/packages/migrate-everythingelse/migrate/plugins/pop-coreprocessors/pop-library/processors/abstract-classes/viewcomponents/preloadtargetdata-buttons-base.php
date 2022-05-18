<?php

use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\RelationalModuleField;

abstract class PoP_Module_Processor_PreloadTargetDataButtonsBase extends PoP_Module_Processor_ButtonsBase
{
    public function getTargetDynamicallyRenderedSubmodules(array $component)
    {
        return array();
    }

    /**
     * @return RelationalModuleField[]
     */
    public function getTargetDynamicallyRenderedSubcomponentSubmodules(array $component)
    {
        return array();
    }

    /**
     * @return RelationalModuleField[]
     */
    public function getRelationalSubmodules(array $component): array
    {
        $ret = parent::getRelationalSubmodules($component);

        // We need to load the data needed by the datum, so that when executing `triggerSelect` in function `renderDBObjectLayoutFromURLParam`
        // the data has already been preloaded
        if ($dynamic_components = $this->getTargetDynamicallyRenderedSubcomponentSubmodules($component)) {
            $ret = array_merge(
                $ret,
                $dynamic_components
            );
        }

        return $ret;
    }

    public function getSubcomponents(array $component): array
    {
        $ret = parent::getSubcomponents($component);

        // We need to load the data needed by the datum, so that when executing `triggerSelect` in function `renderDBObjectLayoutFromURLParam`
        // the data has already been preloaded
        if ($dynamic_components = $this->getTargetDynamicallyRenderedSubmodules($component)) {
            $ret = array_merge(
                $ret,
                $dynamic_components
            );
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {

        // Mark the layouts as needing dynamic data, so the DB data is sent to the webplatform also when doing SSR
        if (defined('POP_SSR_INITIALIZED')) {
            if ($dynamic_components = $this->getTargetDynamicallyRenderedSubmodules($component)) {
                foreach ($dynamic_components as $dynamic_component) {
                    $this->setProp($dynamic_component, $props, 'needs-dynamic-data', true);
                }
            }

            if ($subcomponent_dynamic_templates = $this->getTargetDynamicallyRenderedSubcomponentSubmodules($component)) {
                foreach ($subcomponent_dynamic_templates as $data_field => $components) {
                    foreach ($components as $dynamic_component) {
                        $this->setProp($dynamic_component, $props, 'needs-dynamic-data', true);
                    }
                }
            }
        }

        parent::initModelProps($component, $props);
    }
}
