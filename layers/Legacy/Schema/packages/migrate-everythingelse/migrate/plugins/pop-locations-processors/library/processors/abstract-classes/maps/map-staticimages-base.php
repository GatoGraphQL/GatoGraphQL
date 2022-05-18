<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\Misc\GeneralUtils;

abstract class PoP_Module_Processor_MapStaticImagesBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_Locations_TemplateResourceLoaderProcessor::class, PoP_Locations_TemplateResourceLoaderProcessor::RESOURCE_MAP_STATICIMAGE];
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);

        if ($urlparam = $this->getUrlparamSubmodule($componentVariation)) {
            $ret[] = $urlparam;
        }

        return $ret;
    }

    public function getUrlparamSubmodule(array $componentVariation)
    {
        return null;
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $ret['url'] = $this->getStaticmapUrl($componentVariation, $props);

        if ($urlparam = $this->getUrlparamSubmodule($componentVariation)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['urlparam'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($urlparam);
        }

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        $this->setProp($componentVariation, $props, 'staticmap-size', $this->getStaticmapSize($componentVariation, $props));
        parent::initModelProps($componentVariation, $props);
    }

    protected function getStaticmapSize(array $componentVariation, array &$props)
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

    protected function getStaticmapUrl(array $componentVariation, array &$props)
    {
        $url = 'https://maps.googleapis.com/maps/api/staticmap';
        if (POP_COREPROCESSORS_APIKEY_GOOGLEMAPS) {
            $url .= '?key='.POP_COREPROCESSORS_APIKEY_GOOGLEMAPS;
        }

        $args = [];
        if ($size = $this->getProp($componentVariation, $props, 'staticmap-size')) {
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
