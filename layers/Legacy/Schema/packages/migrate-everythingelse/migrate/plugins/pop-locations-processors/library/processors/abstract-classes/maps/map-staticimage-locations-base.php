<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\RelationalComponentFieldNode;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;

abstract class PoP_Module_Processor_MapStaticImageLocationsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(\PoP\ComponentModel\Component\Component $component, array &$props): ?array
    {
        return [PoP_Locations_TemplateResourceLoaderProcessor::class, PoP_Locations_TemplateResourceLoaderProcessor::RESOURCE_MAP_STATICIMAGE_LOCATIONS];
    }

    /**
     * @return RelationalComponentFieldNode[]
     */
    public function getRelationalComponentFieldNodes(\PoP\ComponentModel\Component\Component $component): array
    {
        $urlparam = $this->getUrlparamSubcomponent($component);
        return [
            new RelationalComponentFieldNode(
                new LeafField(
                    'locations',
                    null,
                    [],
                    [],
                    LocationHelper::getNonSpecificLocation()
                ),
                [
                    $urlparam,
                ]
            ),
        ];
    }

    public function getUrlparamSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        return [PoP_Module_Processor_MapStaticImageURLParams::class, PoP_Module_Processor_MapStaticImageURLParams::COMPONENT_MAP_STATICIMAGE_URLPARAM];
    }

    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $urlparam = $this->getUrlparamSubcomponent($component);
        $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['urlparam'] = \PoP\ComponentModel\Facades\ComponentHelpers\ComponentHelpersFacade::getInstance()->getComponentOutputName($urlparam);

        return $ret;
    }
}
