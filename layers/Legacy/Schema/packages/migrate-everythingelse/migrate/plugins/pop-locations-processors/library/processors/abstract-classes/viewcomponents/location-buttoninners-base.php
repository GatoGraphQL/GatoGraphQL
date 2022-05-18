<?php
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\RelationalModuleField;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class PoP_Module_Processor_LocationViewComponentButtonInnersBase extends PoP_Module_Processor_ButtonInnersBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_Locations_TemplateResourceLoaderProcessor::class, PoP_Locations_TemplateResourceLoaderProcessor::RESOURCE_VIEWCOMPONENT_LOCATIONBUTTONINNER];
    }

    public function getLocationModule(array $component)
    {
        return null;
    }
    public function separator(array $component, array &$props)
    {
        return ' | ';
    }

    public function getFontawesome(array $component, array &$props)
    {
        return 'fa-map-marker';
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        if ($location_component = $this->getLocationModule($component)) {
            $ret['separator'] = $this->separator($component, $props);
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['location-layout'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($location_component);
        } else {
            $ret[GD_JS_TITLES] = array(
                'locations' => TranslationAPIFacade::getInstance()->__('Locations', 'em-popprocessors')
            );
        }

        return $ret;
    }

    /**
     * @return RelationalModuleField[]
     */
    public function getRelationalSubmodules(array $component): array
    {
        if ($location_component = $this->getLocationModule($component)) {
            return [
                new RelationalModuleField(
                    'locations',
                    [
                        $location_component,
                    ]
                ),
            ];
        }
        return parent::getRelationalSubmodules($component);
    }
}
