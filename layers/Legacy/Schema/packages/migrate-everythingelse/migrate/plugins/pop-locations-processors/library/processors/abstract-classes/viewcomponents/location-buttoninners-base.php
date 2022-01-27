<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class PoP_Module_Processor_LocationViewComponentButtonInnersBase extends PoP_Module_Processor_ButtonInnersBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_Locations_TemplateResourceLoaderProcessor::class, PoP_Locations_TemplateResourceLoaderProcessor::RESOURCE_VIEWCOMPONENT_LOCATIONBUTTONINNER];
    }

    public function getLocationModule(array $module)
    {
        return null;
    }
    public function separator(array $module, array &$props)
    {
        return ' | ';
    }

    public function getFontawesome(array $module, array &$props)
    {
        return 'fa-map-marker';
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        if ($location_module = $this->getLocationModule($module)) {
            $ret['separator'] = $this->separator($module, $props);
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['location-layout'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($location_module);
        } else {
            $ret[GD_JS_TITLES] = array(
                'locations' => TranslationAPIFacade::getInstance()->__('Locations', 'em-popprocessors')
            );
        }

        return $ret;
    }

    public function getDomainSwitchingSubmodules(array $module): array
    {
        if ($location_module = $this->getLocationModule($module)) {
            return array(
                'locations' => array(
                    $location_module,
                ),
            );
        }
        return parent::getDomainSwitchingSubmodules($module);
    }
}
