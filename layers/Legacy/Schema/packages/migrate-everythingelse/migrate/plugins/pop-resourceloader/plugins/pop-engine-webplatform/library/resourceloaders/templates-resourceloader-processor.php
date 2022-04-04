<?php

class PoP_FrontEnd_TemplateResourceLoaderProcessor extends PoP_TemplateResourceLoaderProcessor
{
    public final const RESOURCE_EXTENSIONAPPENDABLECLASS = 'extensionappendableclass';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_EXTENSIONAPPENDABLECLASS],
        ];
    }
    
    public function getTemplate(array $resource)
    {
        $templates = array(
            self::RESOURCE_EXTENSIONAPPENDABLECLASS => POP_TEMPLATE_EXTENSIONAPPENDABLECLASS,
        );
        return $templates[$resource[1]];
    }
    
    public function getVersion(array $resource)
    {
        return POP_ENGINEWEBPLATFORM_VERSION;
    }
    
    public function getPath(array $resource)
    {
        return POP_ENGINEWEBPLATFORM_URL.'/js/dist/templates';
    }
    
    public function getDir(array $resource)
    {
        return POP_ENGINEWEBPLATFORM_DIR.'/js/dist/templates';
    }
}


