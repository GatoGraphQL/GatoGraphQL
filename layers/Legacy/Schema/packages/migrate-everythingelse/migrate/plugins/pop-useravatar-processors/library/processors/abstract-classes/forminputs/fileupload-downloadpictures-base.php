<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class PoP_Module_Processor_DownloadPictureFileUploadBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_UserAvatarWebPlatform_TemplateResourceLoaderProcessor::class, PoP_UserAvatarWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_FILEUPLOAD_PICTURE_DOWNLOAD];
    }
    
    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);
        
        $ret[GD_JS_TITLES] = array(
            'avatar' => TranslationAPIFacade::getInstance()->__('Avatar', 'pop-useravatar-processors'),
            'photo' => TranslationAPIFacade::getInstance()->__('Profile photo', 'pop-useravatar-processors'),
            'destroy' => TranslationAPIFacade::getInstance()->__('Delete', 'pop-useravatar-processors')
        );
        if ($rel = gdImageRel()) {
            $ret['image-rel'] = $rel;
        }
        
        return $ret;
    }
}
