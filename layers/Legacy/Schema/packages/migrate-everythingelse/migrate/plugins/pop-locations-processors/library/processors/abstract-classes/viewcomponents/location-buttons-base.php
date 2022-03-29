<?php
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoP\ComponentModel\GraphQLModel\ComponentModelSpec\Ast\RelationalModuleField;
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class PoP_Module_Processor_LocationViewComponentButtonsBase extends PoP_Module_Processor_ViewComponentButtonsBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_Locations_TemplateResourceLoaderProcessor::class, PoP_Locations_TemplateResourceLoaderProcessor::RESOURCE_VIEWCOMPONENT_LOCATIONBUTTON];
    }

    public function getMapscriptSubmodule(array $module)
    {
        return [PoP_Module_Processor_MapScripts::class, PoP_Module_Processor_MapScripts::MODULE_MAP_SCRIPT];
    }

    public function getLocationModule(array $module)
    {
        return null;
    }
    public function getLocationComplementModule(array $module)
    {
        return null;
    }

    public function initMarkers(array $module)
    {

        // When in the Map window, the location link must not initialize the markers, since they are already initialized by the map itself.
        // Do it so, initializes them twice, which leads to problems, like when searching it displays markers from the previous state
        // (which were initialized then drawn then initialized again and remained there in the memory)
        return true;
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        if ($this->initMarkers($module)) {
            $ret[] = [PoP_Module_Processor_MapResetMarkerScripts::class, PoP_Module_Processor_MapResetMarkerScripts::MODULE_MAP_SCRIPT_RESETMARKERS];
            $ret[] = $this->getMapscriptSubmodule($module);
        }

        return $ret;
    }

    /**
     * @return RelationalModuleField[]
     */
    public function getDomainSwitchingSubmodules(array $module): array
    {
        if ($this->showEachLocation($module)) {
            $modules = [];
            if ($location_module = $this->getLocationModule($module)) {
                $modules[] = $location_module;
            }
            if ($location_complement = $this->getLocationComplementModule($module)) {
                $modules[] = $location_complement;
            }

            if ($modules) {
                return [
                    new RelationalModuleField(
                        'locations',
                        $modules
                    ),
                ];
            }
        }

        return parent::getDomainSwitchingSubmodules($module);
    }

    public function getUrlField(array $module)
    {
        return 'locationsmapURL';
    }

    public function getUrl(array $module, array &$props)
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        return RouteUtils::getRouteURL(POP_LOCATIONS_ROUTE_LOCATIONSMAP);
    }

    public function getLinkClass(array $module)
    {
        return 'pop-modalmap-link';
    }

    public function showEachLocation(array $module)
    {
        return true;
    }
    public function showJoinLocations(array $module)
    {
        return true;
    }
    public function getJoinSeparator(array $module)
    {
        return '<i class="fa fa-fw fa-long-arrow-right"></i>';
    }
    public function getEachSeparator(array $module)
    {
        return ' | ';
    }
    public function getComplementSeparator(array $module)
    {
        return ' ';
    }

    public function getTitle(array $module, array &$props)
    {
        return TranslationAPIFacade::getInstance()->__('All Locations', 'em-popprocessors');
    }

    public function getLinktarget(array $module, array &$props)
    {
        return POP_TARGET_MODALS;
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        // If we don't initialize the markers, simply do not send the moduleoutputname for those modules, and they will not be drawn
        if ($this->initMarkers($module)) {
            $map_script = $this->getMapscriptSubmodule($module);
            $resetmarkers = [PoP_Module_Processor_MapResetMarkerScripts::class, PoP_Module_Processor_MapResetMarkerScripts::MODULE_MAP_SCRIPT_RESETMARKERS];
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['map-script'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($map_script);
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['map-script-resetmarkers'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($resetmarkers);
        }

        $ret[GD_JS_TITLES]['locations'] = $this->getTitle($module, $props);

        if ($this->showJoinLocations($module)) {
            $ret['show-join'] = true;
            $ret['join-separator'] = $this->getJoinSeparator($module);
        }
        if ($this->showEachLocation($module)) {
            $ret['show-each'] = true;
            $ret['each-separator'] = $this->getEachSeparator($module);
            $ret['complement-separator'] = $this->getComplementSeparator($module);

            if ($location_module = $this->getLocationModule($module)) {
                $ret[GD_JS_SUBMODULEOUTPUTNAMES]['location-layout'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($location_module);
            }
            if ($location_complement = $this->getLocationComplementModule($module)) {
                $ret[GD_JS_SUBMODULEOUTPUTNAMES]['location-complement'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($location_complement);
            }
        }

        return $ret;
    }
}
