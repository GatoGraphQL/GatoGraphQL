<?php
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoP\ComponentModel\Modules\ModuleUtils;

abstract class PoP_Module_Processor_MapDivsBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_Locations_TemplateResourceLoaderProcessor::class, PoP_Locations_TemplateResourceLoaderProcessor::RESOURCE_MAP_DIV];
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        if ($inners = $this->getInnerSubmodules($module)) {
            $ret = array_merge(
                $ret,
                $inners
            );
        }

        return $ret;
    }

    public function getInnerSubmodules(array $module): array
    {
        return array();
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        $ret = parent::getImmutableConfiguration($module, $props);

        if ($inners = $this->getInnerSubmodules($module)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['inners'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $inners
            );
        }

        return $ret;
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);

        $this->addJsmethod($ret, 'map');

        return $ret;
    }

    public function openOnemarkerInfowindow(array $module, array &$props)
    {
        return true;
    }

    public function initModelProps(array $module, array &$props): void
    {

        // Open the infoWindow automatically when the map has only 1 marker?
        $this->setProp($module, $props, 'open-onemarker-infowindow', $this->openOnemarkerInfowindow($module, $props));
        if ($this->getProp($module, $props, 'open-onemarker-infowindow')) {
            $this->mergeProp(
                $module,
                $props,
                'params',
                array(
                    'data-open-onemarker-infowindow' => true
                )
            );
        }

        parent::initModelProps($module, $props);
    }
}
