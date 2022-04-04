<?php

class PoP_EventsCreation_TemplateResourceLoaderProcessor extends PoP_TemplateResourceLoaderProcessor
{
    public final const RESOURCE_LAYOUTEVENT_TABLECOL = 'layoutevent_tablecol';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_LAYOUTEVENT_TABLECOL],
        ];
    }
    
    public function getTemplate(array $resource)
    {
        $templates = array(
            self::RESOURCE_LAYOUTEVENT_TABLECOL => POP_TEMPLATE_LAYOUTEVENT_TABLECOL,
        );
        return $templates[$resource[1]];
    }
    
    public function getVersion(array $resource)
    {
        return POP_EVENTSCREATIONWEBPLATFORM_VERSION;
    }
    
    public function getPath(array $resource)
    {
        return POP_EVENTSCREATIONWEBPLATFORM_URL.'/js/dist/templates';
    }
    
    public function getDir(array $resource)
    {
        return POP_EVENTSCREATIONWEBPLATFORM_DIR.'/js/dist/templates';
    }
}


