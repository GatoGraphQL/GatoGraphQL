<?php

abstract class GD_EM_Module_Processor_LocationTypeaheadsComponentLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_Locations_TemplateResourceLoaderProcessor::class, PoP_Locations_TemplateResourceLoaderProcessor::RESOURCE_LAYOUTLOCATION_TYPEAHEAD_COMPONENT];
    }
    
    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $module, array &$props): array
    {
        return array('id', 'name', 'address', 'coordinates'); // Coordinates: needed for drawing the selected location on the Google Map, so keep even if it doesn't show in the .tmpl
    }
}
