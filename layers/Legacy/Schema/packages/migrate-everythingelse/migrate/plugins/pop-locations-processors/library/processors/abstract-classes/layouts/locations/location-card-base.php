<?php

abstract class GD_EM_Module_Processor_LocationTypeaheadsSelectedLayoutsBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_Locations_TemplateResourceLoaderProcessor::class, PoP_Locations_TemplateResourceLoaderProcessor::RESOURCE_LAYOUTLOCATION_CARD];
    }
    
    /**
     * @todo Migrate from string to LeafField
     *
     * @return \PoP\GraphQLParser\Spec\Parser\Ast\LeafField[]
     */
    public function getDataFields(array $module, array &$props): array
    {
        return array('id', 'name', 'address', 'coordinates'); // Coordinates: needed for drawing the selected location on the Google Map, so keep even if it doesn't show in the .tmpl
    }
}
