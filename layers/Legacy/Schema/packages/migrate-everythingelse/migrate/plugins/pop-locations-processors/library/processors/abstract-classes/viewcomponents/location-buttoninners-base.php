<?php
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\RelationalModuleField;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class PoP_Module_Processor_LocationViewComponentButtonInnersBase extends PoP_Module_Processor_ButtonInnersBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_Locations_TemplateResourceLoaderProcessor::class, PoP_Locations_TemplateResourceLoaderProcessor::RESOURCE_VIEWCOMPONENT_LOCATIONBUTTONINNER];
    }

    public function getLocationModule(array $componentVariation)
    {
        return null;
    }
    public function separator(array $componentVariation, array &$props)
    {
        return ' | ';
    }

    public function getFontawesome(array $componentVariation, array &$props)
    {
        return 'fa-map-marker';
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        if ($location_componentVariation = $this->getLocationModule($componentVariation)) {
            $ret['separator'] = $this->separator($componentVariation, $props);
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['location-layout'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($location_componentVariation);
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
    public function getRelationalSubmodules(array $componentVariation): array
    {
        if ($location_componentVariation = $this->getLocationModule($componentVariation)) {
            return [
                new RelationalModuleField(
                    'locations',
                    [
                        $location_componentVariation,
                    ]
                ),
            ];
        }
        return parent::getRelationalSubmodules($componentVariation);
    }
}
