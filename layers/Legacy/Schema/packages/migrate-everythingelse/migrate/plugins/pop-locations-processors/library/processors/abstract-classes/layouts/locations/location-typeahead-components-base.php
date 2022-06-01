<?php

abstract class GD_EM_Module_Processor_LocationTypeaheadsComponentLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(\PoP\ComponentModel\Component\Component $component, array &$props): ?array
    {
        return [PoP_Locations_TemplateResourceLoaderProcessor::class, PoP_Locations_TemplateResourceLoaderProcessor::RESOURCE_LAYOUTLOCATION_TYPEAHEAD_COMPONENT];
    }
    
    /**
     * @todo Migrate from string to LeafComponentField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafComponentField[]
     */
    public function getLeafComponentFields(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        return array('id', 'name', 'address', 'coordinates'); // Coordinates: needed for drawing the selected location on the Google Map, so keep even if it doesn't show in the .tmpl
    }
}
