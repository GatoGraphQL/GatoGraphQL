<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\RelationalModuleField;
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class PoP_Module_Processor_LocationViewComponentButtonsBase extends PoP_Module_Processor_ViewComponentButtonsBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_Locations_TemplateResourceLoaderProcessor::class, PoP_Locations_TemplateResourceLoaderProcessor::RESOURCE_VIEWCOMPONENT_LOCATIONBUTTON];
    }

    public function getMapscriptSubcomponent(array $component)
    {
        return [PoP_Module_Processor_MapScripts::class, PoP_Module_Processor_MapScripts::COMPONENT_MAP_SCRIPT];
    }

    public function getLocationModule(array $component)
    {
        return null;
    }
    public function getLocationComplementModule(array $component)
    {
        return null;
    }

    public function initMarkers(array $component)
    {

        // When in the Map window, the location link must not initialize the markers, since they are already initialized by the map itself.
        // Do it so, initializes them twice, which leads to problems, like when searching it displays markers from the previous state
        // (which were initialized then drawn then initialized again and remained there in the memory)
        return true;
    }

    public function getSubcomponents(array $component): array
    {
        $ret = parent::getSubcomponents($component);

        if ($this->initMarkers($component)) {
            $ret[] = [PoP_Module_Processor_MapResetMarkerScripts::class, PoP_Module_Processor_MapResetMarkerScripts::COMPONENT_MAP_SCRIPT_RESETMARKERS];
            $ret[] = $this->getMapscriptSubcomponent($component);
        }

        return $ret;
    }

    /**
     * @return RelationalModuleField[]
     */
    public function getRelationalSubcomponents(array $component): array
    {
        if ($this->showEachLocation($component)) {
            $components = [];
            if ($location_component = $this->getLocationModule($component)) {
                $components[] = $location_component;
            }
            if ($location_complement = $this->getLocationComplementModule($component)) {
                $components[] = $location_complement;
            }

            if ($components) {
                return [
                    new RelationalModuleField(
                        'locations',
                        $components
                    ),
                ];
            }
        }

        return parent::getRelationalSubcomponents($component);
    }

    public function getUrlField(array $component)
    {
        return 'locationsmapURL';
    }

    public function getUrl(array $component, array &$props)
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        return RouteUtils::getRouteURL(POP_LOCATIONS_ROUTE_LOCATIONSMAP);
    }

    public function getLinkClass(array $component)
    {
        return 'pop-modalmap-link';
    }

    public function showEachLocation(array $component)
    {
        return true;
    }
    public function showJoinLocations(array $component)
    {
        return true;
    }
    public function getJoinSeparator(array $component)
    {
        return '<i class="fa fa-fw fa-long-arrow-right"></i>';
    }
    public function getEachSeparator(array $component)
    {
        return ' | ';
    }
    public function getComplementSeparator(array $component)
    {
        return ' ';
    }

    public function getTitle(array $component, array &$props)
    {
        return TranslationAPIFacade::getInstance()->__('All Locations', 'em-popprocessors');
    }

    public function getLinktarget(array $component, array &$props)
    {
        return POP_TARGET_MODALS;
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        // If we don't initialize the markers, simply do not send the componentoutputname for those modules, and they will not be drawn
        if ($this->initMarkers($component)) {
            $map_script = $this->getMapscriptSubcomponent($component);
            $resetmarkers = [PoP_Module_Processor_MapResetMarkerScripts::class, PoP_Module_Processor_MapResetMarkerScripts::COMPONENT_MAP_SCRIPT_RESETMARKERS];
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['map-script'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($map_script);
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['map-script-resetmarkers'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($resetmarkers);
        }

        $ret[GD_JS_TITLES]['locations'] = $this->getTitle($component, $props);

        if ($this->showJoinLocations($component)) {
            $ret['show-join'] = true;
            $ret['join-separator'] = $this->getJoinSeparator($component);
        }
        if ($this->showEachLocation($component)) {
            $ret['show-each'] = true;
            $ret['each-separator'] = $this->getEachSeparator($component);
            $ret['complement-separator'] = $this->getComplementSeparator($component);

            if ($location_component = $this->getLocationModule($component)) {
                $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['location-layout'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($location_component);
            }
            if ($location_complement = $this->getLocationComplementModule($component)) {
                $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['location-complement'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($location_complement);
            }
        }

        return $ret;
    }
}
