<?php

abstract class GD_EM_Module_Processor_LocationTypeaheadsSelectedLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_Locations_TemplateResourceLoaderProcessor::class, PoP_Locations_TemplateResourceLoaderProcessor::RESOURCE_LAYOUTLOCATION_CARD];
    }
    
    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $component, array &$props): array
    {
        return array('id', 'name', 'address', 'coordinates'); // Coordinates: needed for drawing the selected location on the Google Map, so keep even if it doesn't show in the .tmpl
    }
}
