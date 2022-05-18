<?php

class PoP_Module_Processor_PreviewNotificationLayouts extends PoP_Module_Processor_PreviewNotificationLayoutsBase
{
    public final const COMPONENT_LAYOUT_PREVIEWNOTIFICATION_DETAILS = 'layout-previewnotification-details';
    public final const COMPONENT_LAYOUT_PREVIEWNOTIFICATION_LIST = 'layout-previewnotification-list';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_PREVIEWNOTIFICATION_DETAILS],
            [self::class, self::COMPONENT_LAYOUT_PREVIEWNOTIFICATION_LIST],
        );
    }
    
    public function getUserAvatarModule(array $component)
    {
        if (defined('POP_AVATARPROCESSORS_INITIALIZED')) {
            switch ($component[1]) {
                case self::COMPONENT_LAYOUT_PREVIEWNOTIFICATION_DETAILS:
                    return [PoP_Module_Processor_PostAuthorAvatarLayouts::class, PoP_Module_Processor_PostAuthorAvatarLayouts::COMPONENT_LAYOUTPOST_AUTHORAVATAR60];
            }
        }

        return parent::getUserAvatarModule($component);
    }
}


