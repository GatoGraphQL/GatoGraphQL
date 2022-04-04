<?php

class PoP_SocialLoginWebPlatform_TemplateResourceLoaderProcessor extends PoP_TemplateResourceLoaderProcessor
{
    public final const RESOURCE_SOCIALLOGIN_NETWORKLINKS = 'sociallogin_networklinks';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_SOCIALLOGIN_NETWORKLINKS],
        ];
    }
    
    public function getTemplate(array $resource)
    {
        $templates = array(
            self::RESOURCE_SOCIALLOGIN_NETWORKLINKS => POP_TEMPLATE_SOCIALLOGIN_NETWORKLINKS,
        );
        return $templates[$resource[1]];
    }
    
    public function getVersion(array $resource)
    {
        return POP_SOCIALLOGINWEBPLATFORM_VERSION;
    }
    
    public function getPath(array $resource)
    {
        return POP_SOCIALLOGINWEBPLATFORM_URL.'/js/dist/templates';
    }
    
    public function getDir(array $resource)
    {
        return POP_SOCIALLOGINWEBPLATFORM_DIR.'/js/dist/templates';
    }
}


