<?php

class PoP_AAL_Processors_TemplateResourceLoaderProcessor extends PoP_TemplateResourceLoaderProcessor
{
    public final const RESOURCE_LAYOUT_PREVIEWNOTIFICATION = 'layout_previewnotification';
    public final const RESOURCE_LAYOUT_NOTIFICATIONTIME = 'layout_notificationtime';
    public final const RESOURCE_LAYOUT_NOTIFICATIONICON = 'layout_notificationicon';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_LAYOUT_PREVIEWNOTIFICATION],
            [self::class, self::RESOURCE_LAYOUT_NOTIFICATIONTIME],
            [self::class, self::RESOURCE_LAYOUT_NOTIFICATIONICON],
        ];
    }
    
    public function getTemplate(array $resource)
    {
        $templates = array(
            self::RESOURCE_LAYOUT_PREVIEWNOTIFICATION => POP_TEMPLATE_LAYOUT_PREVIEWNOTIFICATION,
            self::RESOURCE_LAYOUT_NOTIFICATIONTIME => POP_TEMPLATE_LAYOUT_NOTIFICATIONTIME,
            self::RESOURCE_LAYOUT_NOTIFICATIONICON => POP_TEMPLATE_LAYOUT_NOTIFICATIONICON,
        );
        return $templates[$resource[1]];
    }
    
    public function getVersion(array $resource)
    {
        return POP_NOTIFICATIONSWEBPLATFORM_VERSION;
    }
    
    public function getPath(array $resource)
    {
        return POP_NOTIFICATIONSWEBPLATFORM_URL.'/js/dist/templates';
    }
    
    public function getDir(array $resource)
    {
        return POP_NOTIFICATIONSWEBPLATFORM_DIR.'/js/dist/templates';
    }
}


