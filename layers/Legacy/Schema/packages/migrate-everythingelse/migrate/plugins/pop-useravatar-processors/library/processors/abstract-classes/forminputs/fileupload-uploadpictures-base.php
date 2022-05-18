<?php

abstract class PoP_Module_Processor_UploadPictureFileUploadBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_UserAvatarWebPlatform_TemplateResourceLoaderProcessor::class, PoP_UserAvatarWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_FILEUPLOAD_PICTURE_UPLOAD];
    }
}
