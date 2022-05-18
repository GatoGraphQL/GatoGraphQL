<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\RelationalModuleField;
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class PoP_Module_Processor_LocationViewComponentButtonsBase extends PoP_Module_Processor_ViewComponentButtonsBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_Locations_TemplateResourceLoaderProcessor::class, PoP_Locations_TemplateResourceLoaderProcessor::RESOURCE_VIEWCOMPONENT_LOCATIONBUTTON];
    }

    public function getMapscriptSubmodule(array $componentVariation)
    {
        return [PoP_Module_Processor_MapScripts::class, PoP_Module_Processor_MapScripts::MODULE_MAP_SCRIPT];
    }

    public function getLocationModule(array $componentVariation)
    {
        return null;
    }
    public function getLocationComplementModule(array $componentVariation)
    {
        return null;
    }

    public function initMarkers(array $componentVariation)
    {

        // When in the Map window, the location link must not initialize the markers, since they are already initialized by the map itself.
        // Do it so, initializes them twice, which leads to problems, like when searching it displays markers from the previous state
        // (which were initialized then drawn then initialized again and remained there in the memory)
        return true;
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);

        if ($this->initMarkers($componentVariation)) {
            $ret[] = [PoP_Module_Processor_MapResetMarkerScripts::class, PoP_Module_Processor_MapResetMarkerScripts::MODULE_MAP_SCRIPT_RESETMARKERS];
            $ret[] = $this->getMapscriptSubmodule($componentVariation);
        }

        return $ret;
    }

    /**
     * @return RelationalModuleField[]
     */
    public function getRelationalSubmodules(array $componentVariation): array
    {
        if ($this->showEachLocation($componentVariation)) {
            $componentVariations = [];
            if ($location_module = $this->getLocationModule($componentVariation)) {
                $componentVariations[] = $location_module;
            }
            if ($location_complement = $this->getLocationComplementModule($componentVariation)) {
                $componentVariations[] = $location_complement;
            }

            if ($componentVariations) {
                return [
                    new RelationalModuleField(
                        'locations',
                        $componentVariations
                    ),
                ];
            }
        }

        return parent::getRelationalSubmodules($componentVariation);
    }

    public function getUrlField(array $componentVariation)
    {
        return 'locationsmapURL';
    }

    public function getUrl(array $componentVariation, array &$props)
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        return RouteUtils::getRouteURL(POP_LOCATIONS_ROUTE_LOCATIONSMAP);
    }

    public function getLinkClass(array $componentVariation)
    {
        return 'pop-modalmap-link';
    }

    public function showEachLocation(array $componentVariation)
    {
        return true;
    }
    public function showJoinLocations(array $componentVariation)
    {
        return true;
    }
    public function getJoinSeparator(array $componentVariation)
    {
        return '<i class="fa fa-fw fa-long-arrow-right"></i>';
    }
    public function getEachSeparator(array $componentVariation)
    {
        return ' | ';
    }
    public function getComplementSeparator(array $componentVariation)
    {
        return ' ';
    }

    public function getTitle(array $componentVariation, array &$props)
    {
        return TranslationAPIFacade::getInstance()->__('All Locations', 'em-popprocessors');
    }

    public function getLinktarget(array $componentVariation, array &$props)
    {
        return POP_TARGET_MODALS;
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        // If we don't initialize the markers, simply do not send the moduleoutputname for those modules, and they will not be drawn
        if ($this->initMarkers($componentVariation)) {
            $map_script = $this->getMapscriptSubmodule($componentVariation);
            $resetmarkers = [PoP_Module_Processor_MapResetMarkerScripts::class, PoP_Module_Processor_MapResetMarkerScripts::MODULE_MAP_SCRIPT_RESETMARKERS];
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['map-script'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($map_script);
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['map-script-resetmarkers'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($resetmarkers);
        }

        $ret[GD_JS_TITLES]['locations'] = $this->getTitle($componentVariation, $props);

        if ($this->showJoinLocations($componentVariation)) {
            $ret['show-join'] = true;
            $ret['join-separator'] = $this->getJoinSeparator($componentVariation);
        }
        if ($this->showEachLocation($componentVariation)) {
            $ret['show-each'] = true;
            $ret['each-separator'] = $this->getEachSeparator($componentVariation);
            $ret['complement-separator'] = $this->getComplementSeparator($componentVariation);

            if ($location_module = $this->getLocationModule($componentVariation)) {
                $ret[GD_JS_SUBMODULEOUTPUTNAMES]['location-layout'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($location_module);
            }
            if ($location_complement = $this->getLocationComplementModule($componentVariation)) {
                $ret[GD_JS_SUBMODULEOUTPUTNAMES]['location-complement'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($location_complement);
            }
        }

        return $ret;
    }
}
