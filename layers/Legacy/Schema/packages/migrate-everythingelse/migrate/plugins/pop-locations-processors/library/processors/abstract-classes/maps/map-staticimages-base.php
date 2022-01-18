<?php
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoP\ComponentModel\Misc\GeneralUtils;

abstract class PoP_Module_Processor_MapStaticImagesBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_Locations_TemplateResourceLoaderProcessor::class, PoP_Locations_TemplateResourceLoaderProcessor::RESOURCE_MAP_STATICIMAGE];
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        if ($urlparam = $this->getUrlparamSubmodule($module)) {
            $ret[] = $urlparam;
        }

        return $ret;
    }

    public function getUrlparamSubmodule(array $module)
    {
        return null;
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        $ret['url'] = $this->getStaticmapUrl($module, $props);

        if ($urlparam = $this->getUrlparamSubmodule($module)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['urlparam'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($urlparam);
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        $this->setProp($module, $props, 'staticmap-size', $this->getStaticmapSize($module, $props));
        parent::initModelProps($module, $props);
    }

    protected function getStaticmapSize(array $module, array &$props)
    {
        return '640x400';
    }

    protected function getStaticmapType()
    {
        return 'roadmap';
    }

    protected function getStaticmapCenter()
    {
        return null;
    }

    protected function getStaticmapZoom()
    {
        return null;
    }

    protected function getStaticmapUrl(array $module, array &$props)
    {
        $url = 'https://maps.googleapis.com/maps/api/staticmap';
        if (POP_COREPROCESSORS_APIKEY_GOOGLEMAPS) {
            $url .= '?key='.POP_COREPROCESSORS_APIKEY_GOOGLEMAPS;
        }

        $args = [];
        if ($size = $this->getProp($module, $props, 'staticmap-size')) {
            $args['size'] = $size;
        }

        if ($type = $this->getStaticmapType()) {
            $args['maptype'] = $type;
        }

        if ($center = $this->getStaticmapCenter()) {
            $args['center'] = $center;
        }

        if ($zoom = $this->getStaticmapZoom()) {
            $args['zoom'] = $zoom;
        }

        return GeneralUtils::addQueryArgs($args, $url);
    }
}
