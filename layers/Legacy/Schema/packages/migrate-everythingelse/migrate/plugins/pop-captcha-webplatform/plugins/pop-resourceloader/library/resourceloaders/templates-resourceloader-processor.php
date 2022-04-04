<?php

class PoP_CaptchaWebPlatform_TemplateResourceLoaderProcessor extends PoP_TemplateResourceLoaderProcessor
{
    public final const RESOURCE_FORMINPUT_CAPTCHA = 'forminput_captcha';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_FORMINPUT_CAPTCHA],
        ];
    }
    
    public function getTemplate(array $resource)
    {
        $templates = array(
            self::RESOURCE_FORMINPUT_CAPTCHA => POP_TEMPLATE_FORMINPUT_CAPTCHA,
        );
        return $templates[$resource[1]];
    }
    
    public function getVersion(array $resource)
    {
        return POP_CAPTCHAWEBPLATFORM_VERSION;
    }
    
    public function getPath(array $resource)
    {
        return POP_CAPTCHAWEBPLATFORM_URL.'/js/dist/templates';
    }
    
    public function getDir(array $resource)
    {
        return POP_CAPTCHAWEBPLATFORM_DIR.'/js/dist/templates';
    }
}


