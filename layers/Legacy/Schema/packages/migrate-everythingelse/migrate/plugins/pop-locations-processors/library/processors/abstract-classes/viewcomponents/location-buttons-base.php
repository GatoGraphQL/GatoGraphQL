<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\RelationalComponentField;
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class PoP_Module_Processor_LocationViewComponentButtonsBase extends PoP_Module_Processor_ViewComponentButtonsBase
{
    public function getTemplateResource(\PoP\ComponentModel\Component\Component $component, array &$props): ?array
    {
        return [PoP_Locations_TemplateResourceLoaderProcessor::class, PoP_Locations_TemplateResourceLoaderProcessor::RESOURCE_VIEWCOMPONENT_LOCATIONBUTTON];
    }

    public function getMapscriptSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        return [PoP_Module_Processor_MapScripts::class, PoP_Module_Processor_MapScripts::COMPONENT_MAP_SCRIPT];
    }

    public function getLocationComponent(\PoP\ComponentModel\Component\Component $component)
    {
        return null;
    }
    public function getLocationComplementComponent(\PoP\ComponentModel\Component\Component $component)
    {
        return null;
    }

    public function initMarkers(\PoP\ComponentModel\Component\Component $component)
    {

        // When in the Map window, the location link must not initialize the markers, since they are already initialized by the map itself.
        // Do it so, initializes them twice, which leads to problems, like when searching it displays markers from the previous state
        // (which were initialized then drawn then initialized again and remained there in the memory)
        return true;
    }

    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);

        if ($this->initMarkers($component)) {
            $ret[] = [PoP_Module_Processor_MapResetMarkerScripts::class, PoP_Module_Processor_MapResetMarkerScripts::COMPONENT_MAP_SCRIPT_RESETMARKERS];
            $ret[] = $this->getMapscriptSubcomponent($component);
        }

        return $ret;
    }

    /**
     * @return RelationalComponentField[]
     */
    public function getRelationalComponentFields(\PoP\ComponentModel\Component\Component $component): array
    {
        if ($this->showEachLocation($component)) {
            $components = [];
            if ($location_component = $this->getLocationComponent($component)) {
                $components[] = $location_component;
            }
            if ($location_complement = $this->getLocationComplementComponent($component)) {
                $components[] = $location_complement;
            }

            if ($components) {
                return [
                    new RelationalComponentField(
                        'locations',
                        $components
                    ),
                ];
            }
        }

        return parent::getRelationalComponentFields($component);
    }

    public function getUrlField(\PoP\ComponentModel\Component\Component $component)
    {
        return 'locationsmapURL';
    }

    public function getUrl(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        return RouteUtils::getRouteURL(POP_LOCATIONS_ROUTE_LOCATIONSMAP);
    }

    public function getLinkClass(\PoP\ComponentModel\Component\Component $component)
    {
        return 'pop-modalmap-link';
    }

    public function showEachLocation(\PoP\ComponentModel\Component\Component $component)
    {
        return true;
    }
    public function showJoinLocations(\PoP\ComponentModel\Component\Component $component)
    {
        return true;
    }
    public function getJoinSeparator(\PoP\ComponentModel\Component\Component $component)
    {
        return '<i class="fa fa-fw fa-long-arrow-right"></i>';
    }
    public function getEachSeparator(\PoP\ComponentModel\Component\Component $component)
    {
        return ' | ';
    }
    public function getComplementSeparator(\PoP\ComponentModel\Component\Component $component)
    {
        return ' ';
    }

    public function getTitle(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return TranslationAPIFacade::getInstance()->__('All Locations', 'em-popprocessors');
    }

    public function getLinktarget(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return POP_TARGET_MODALS;
    }

    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        // If we don't initialize the markers, simply do not send the componentoutputname for those modules, and they will not be drawn
        if ($this->initMarkers($component)) {
            $map_script = $this->getMapscriptSubcomponent($component);
            $resetmarkers = [PoP_Module_Processor_MapResetMarkerScripts::class, PoP_Module_Processor_MapResetMarkerScripts::COMPONENT_MAP_SCRIPT_RESETMARKERS];
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['map-script'] = \PoP\ComponentModel\Facades\ComponentHelpers\ComponentHelpersFacade::getInstance()->getComponentOutputName($map_script);
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['map-script-resetmarkers'] = \PoP\ComponentModel\Facades\ComponentHelpers\ComponentHelpersFacade::getInstance()->getComponentOutputName($resetmarkers);
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

            if ($location_component = $this->getLocationComponent($component)) {
                $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['location-layout'] = \PoP\ComponentModel\Facades\ComponentHelpers\ComponentHelpersFacade::getInstance()->getComponentOutputName($location_component);
            }
            if ($location_complement = $this->getLocationComplementComponent($component)) {
                $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['location-complement'] = \PoP\ComponentModel\Facades\ComponentHelpers\ComponentHelpersFacade::getInstance()->getComponentOutputName($location_complement);
            }
        }

        return $ret;
    }
}
