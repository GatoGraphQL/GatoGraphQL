<?php

class PoP_UserLogin_TemplateResourceLoaderProcessor extends PoP_TemplateResourceLoaderProcessor
{
    public final const RESOURCE_USERLOGGEDIN = 'userloggedin';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_USERLOGGEDIN],
        ];
    }
    
    public function getTemplate(array $resource)
    {
        $templates = array(
            self::RESOURCE_USERLOGGEDIN => POP_TEMPLATE_USERLOGGEDIN,
        );
        return $templates[$resource[1]];
    }
    
    public function getVersion(array $resource)
    {
        return POP_USERLOGINWEBPLATFORM_VERSION;
    }
    
    public function getPath(array $resource)
    {
        return POP_USERLOGINWEBPLATFORM_URL.'/js/dist/templates';
    }
    
    public function getDir(array $resource)
    {
        return POP_USERLOGINWEBPLATFORM_DIR.'/js/dist/templates';
    }
}


