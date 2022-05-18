<?php

class PoP_UserAvatarProcessors_Module_Processor_UserFeedbackMessageInners extends PoP_Module_Processor_ActionExecutionFeedbackMessageInnersBase
{
    public final const MODULE_FEEDBACKMESSAGEINNER_USERAVATAR_UPDATE = 'feedbackmessageinner-useravatar-update';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FEEDBACKMESSAGEINNER_USERAVATAR_UPDATE],
        );
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        $layouts = array(
            self::MODULE_FEEDBACKMESSAGEINNER_USERAVATAR_UPDATE => [PoP_UserAvatarProcessors_Module_Processor_UserFeedbackMessageAlertLayouts::class, PoP_UserAvatarProcessors_Module_Processor_UserFeedbackMessageAlertLayouts::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_USERAVATAR_UPDATE],
        );

        if ($layout = $layouts[$component[1]] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}



