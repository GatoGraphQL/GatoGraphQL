<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\Misc\GeneralUtils;

abstract class PoP_Module_Processor_MapStaticImagesBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_Locations_TemplateResourceLoaderProcessor::class, PoP_Locations_TemplateResourceLoaderProcessor::RESOURCE_MAP_STATICIMAGE];
    }

    public function getSubcomponents(array $component): array
    {
        $ret = parent::getSubcomponents($component);

        if ($urlparam = $this->getUrlparamSubmodule($component)) {
            $ret[] = $urlparam;
        }

        return $ret;
    }

    public function getUrlparamSubmodule(array $component)
    {
        return null;
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $ret['url'] = $this->getStaticmapUrl($component, $props);

        if ($urlparam = $this->getUrlparamSubmodule($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['urlparam'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($urlparam);
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        $this->setProp($component, $props, 'staticmap-size', $this->getStaticmapSize($component, $props));
        parent::initModelProps($component, $props);
    }

    protected function getStaticmapSize(array $component, array &$props)
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

    protected function getStaticmapUrl(array $component, array &$props)
    {
        $url = 'https://maps.googleapis.com/maps/api/staticmap';
        if (POP_COREPROCESSORS_APIKEY_GOOGLEMAPS) {
            $url .= '?key='.POP_COREPROCESSORS_APIKEY_GOOGLEMAPS;
        }

        $args = [];
        if ($size = $this->getProp($component, $props, 'staticmap-size')) {
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
