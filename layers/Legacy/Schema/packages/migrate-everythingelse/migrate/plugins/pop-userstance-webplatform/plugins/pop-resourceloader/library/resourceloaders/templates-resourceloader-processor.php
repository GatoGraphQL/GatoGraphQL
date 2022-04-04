<?php

class PoP_Application_UserStance_TemplateResourceLoaderProcessor extends PoP_TemplateResourceLoaderProcessor
{
    public final const RESOURCE_LAYOUTSTANCE = 'layoutstance';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_LAYOUTSTANCE],
        ];
    }
    
    public function getTemplate(array $resource)
    {
        $templates = array(
            self::RESOURCE_LAYOUTSTANCE => POP_TEMPLATE_LAYOUTSTANCE,
        );
        return $templates[$resource[1]];
    }
    
    public function getVersion(array $resource)
    {
        return POP_USERSTANCEWEBPLATFORM_VERSION;
    }
    
    public function getPath(array $resource)
    {
        return POP_USERSTANCEWEBPLATFORM_URL.'/js/dist/templates';
    }
    
    public function getDir(array $resource)
    {
        return POP_USERSTANCEWEBPLATFORM_DIR.'/js/dist/templates';
    }
}


