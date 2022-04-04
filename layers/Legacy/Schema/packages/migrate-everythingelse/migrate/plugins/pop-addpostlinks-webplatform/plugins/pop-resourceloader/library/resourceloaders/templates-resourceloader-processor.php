<?php

class PoP_AddPostLinksWebPlatform_TemplateResourceLoaderProcessor extends PoP_TemplateResourceLoaderProcessor
{
    public final const RESOURCE_LAYOUT_LINKFRAME = 'layout_linkframe';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_LAYOUT_LINKFRAME],
        ];
    }
    
    public function getTemplate(array $resource)
    {
        $templates = array(
            self::RESOURCE_LAYOUT_LINKFRAME => POP_TEMPLATE_LAYOUT_LINKFRAME,
        );
        return $templates[$resource[1]];
    }
    
    public function getVersion(array $resource)
    {
        return POP_ADDPOSTLINKSWEBPLATFORM_VERSION;
    }
    
    public function getPath(array $resource)
    {
        return POP_ADDPOSTLINKSWEBPLATFORM_URL.'/js/dist/templates';
    }
    
    public function getDir(array $resource)
    {
        return POP_ADDPOSTLINKSWEBPLATFORM_DIR.'/js/dist/templates';
    }

    public function getDependencies(array $resource)
    {
        $dependencies = parent::getDependencies($resource);
    
        switch ($resource[1]) {
            case self::RESOURCE_LAYOUT_LINKFRAME:
                $dependencies[] = [PoP_CoreProcessors_HandlebarsHelpersJSResourceLoaderProcessor::class, PoP_CoreProcessors_HandlebarsHelpersJSResourceLoaderProcessor::RESOURCE_HANDLEBARSHELPERS_OPERATORS];
                break;
        }

        return $dependencies;
    }
}


