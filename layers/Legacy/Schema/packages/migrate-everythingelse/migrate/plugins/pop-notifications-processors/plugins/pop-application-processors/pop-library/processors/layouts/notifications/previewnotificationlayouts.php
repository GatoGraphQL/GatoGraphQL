<?php

class PoP_Module_Processor_PreviewNotificationLayouts extends PoP_Module_Processor_PreviewNotificationLayoutsBase
{
    public final const MODULE_LAYOUT_PREVIEWNOTIFICATION_DETAILS = 'layout-previewnotification-details';
    public final const MODULE_LAYOUT_PREVIEWNOTIFICATION_LIST = 'layout-previewnotification-list';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_PREVIEWNOTIFICATION_DETAILS],
            [self::class, self::MODULE_LAYOUT_PREVIEWNOTIFICATION_LIST],
        );
    }
    
    public function getUserAvatarModule(array $module)
    {
        if (defined('POP_AVATARPROCESSORS_INITIALIZED')) {
            switch ($module[1]) {
                case self::MODULE_LAYOUT_PREVIEWNOTIFICATION_DETAILS:
                    return [PoP_Module_Processor_PostAuthorAvatarLayouts::class, PoP_Module_Processor_PostAuthorAvatarLayouts::MODULE_LAYOUTPOST_AUTHORAVATAR60];
            }
        }

        return parent::getUserAvatarModule($module);
    }
}


