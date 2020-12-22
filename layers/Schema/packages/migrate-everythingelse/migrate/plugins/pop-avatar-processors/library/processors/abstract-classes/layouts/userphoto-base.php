<?php

abstract class PoP_Module_Processor_UserPhotoLayoutsBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_AvatarWebPlatform_TemplateResourceLoaderProcessor::class, PoP_AvatarWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_AUTHOR_USERPHOTO];
    }

    public function getDataFields(array $module, array &$props): array
    {
        return array('userphoto', 'displayName');
    }
}
