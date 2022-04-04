<?php

class PoP_AddLocations_TemplateResourceLoaderProcessor extends PoP_TemplateResourceLoaderProcessor
{
    public final const RESOURCE_FRAME_CREATELOCATIONMAP = 'frame_createlocationmap';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_FRAME_CREATELOCATIONMAP],
        ];
    }
    
    public function getTemplate(array $resource)
    {
        $templates = array(
            self::RESOURCE_FRAME_CREATELOCATIONMAP => POP_TEMPLATE_FRAME_CREATELOCATIONMAP,
        );
        return $templates[$resource[1]];
    }
    
    public function getVersion(array $resource)
    {
        return POP_ADDLOCATIONSWEBPLATFORM_VERSION;
    }
    
    public function getPath(array $resource)
    {
        return POP_ADDLOCATIONSWEBPLATFORM_URL.'/js/dist/templates';
    }
    
    public function getDir(array $resource)
    {
        return POP_ADDLOCATIONSWEBPLATFORM_DIR.'/js/dist/templates';
    }
}


