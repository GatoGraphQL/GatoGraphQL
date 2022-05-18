<?php

class PoP_UserAvatarProcessors_Module_Processor_UserFeedbackMessageAlertLayouts extends PoP_Module_Processor_FeedbackMessageAlertLayoutsBase
{
    public final const MODULE_LAYOUT_FEEDBACKMESSAGEALERT_USERAVATAR_UPDATE = 'layout-feedbackmessagealert-useravatar-update';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_USERAVATAR_UPDATE],
        );
    }

    public function getLayoutSubmodule(array $module)
    {
        $layouts = array(
            self::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_USERAVATAR_UPDATE => [PoP_UserAvatarProcessors_Module_Processor_UserFeedbackMessageLayouts::class, PoP_UserAvatarProcessors_Module_Processor_UserFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_USERAVATAR_UPDATE],
        );

        if ($layout = $layouts[$module[1]] ?? null) {
            return $layout;
        }

        return parent::getLayoutSubmodule($module);
    }
}



