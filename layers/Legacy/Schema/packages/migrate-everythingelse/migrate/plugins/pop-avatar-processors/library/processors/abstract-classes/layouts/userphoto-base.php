<?php

abstract class PoP_Module_Processor_UserPhotoLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_AvatarWebPlatform_TemplateResourceLoaderProcessor::class, PoP_AvatarWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_AUTHOR_USERPHOTO];
    }

    /**
     * @todo Migrate from string to LeafComponentField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafComponentField[]
     */
    public function getLeafComponentFields(array $component, array &$props): array
    {
        return array('userphoto', 'displayName');
    }
}
