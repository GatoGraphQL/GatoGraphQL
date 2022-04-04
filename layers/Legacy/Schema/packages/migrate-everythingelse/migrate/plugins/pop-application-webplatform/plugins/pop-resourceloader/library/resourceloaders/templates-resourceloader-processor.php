<?php

class PoP_ApplicationProcessors_TemplateResourceLoaderProcessor extends PoP_TemplateResourceLoaderProcessor
{
    public final const RESOURCE_LAYOUT_LINK_ACCESS = 'layout_link_access';
    public final const RESOURCE_LAYOUT_VOLUNTEERTAG = 'layout_volunteertag';
    public final const RESOURCE_SPEECHBUBBLE = 'speechbubble';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_LAYOUT_LINK_ACCESS],
            [self::class, self::RESOURCE_LAYOUT_VOLUNTEERTAG],
            [self::class, self::RESOURCE_SPEECHBUBBLE],
        ];
    }
    
    public function getTemplate(array $resource)
    {
        $templates = array(
            self::RESOURCE_LAYOUT_LINK_ACCESS => POP_TEMPLATE_LAYOUT_LINK_ACCESS,
            self::RESOURCE_LAYOUT_VOLUNTEERTAG => POP_TEMPLATE_LAYOUT_VOLUNTEERTAG,
            self::RESOURCE_SPEECHBUBBLE => POP_TEMPLATE_SPEECHBUBBLE,
        );
        return $templates[$resource[1]];
    }
    
    public function getVersion(array $resource)
    {
        return POP_APPLICATIONWEBPLATFORM_VERSION;
    }
    
    public function getPath(array $resource)
    {
        return POP_APPLICATIONWEBPLATFORM_URL.'/js/dist/templates';
    }
    
    public function getDir(array $resource)
    {
        return POP_APPLICATIONWEBPLATFORM_DIR.'/js/dist/templates';
    }
}


