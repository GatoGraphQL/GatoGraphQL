<?php

class PoP_UserAvatarProcessors_Module_Processor_UserFeedbackMessageInners extends PoP_Module_Processor_ActionExecutionFeedbackMessageInnersBase
{
    public final const COMPONENT_FEEDBACKMESSAGEINNER_USERAVATAR_UPDATE = 'feedbackmessageinner-useravatar-update';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FEEDBACKMESSAGEINNER_USERAVATAR_UPDATE],
        );
    }

    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getLayoutSubcomponents($component);

        $layouts = array(
            self::COMPONENT_FEEDBACKMESSAGEINNER_USERAVATAR_UPDATE => [PoP_UserAvatarProcessors_Module_Processor_UserFeedbackMessageAlertLayouts::class, PoP_UserAvatarProcessors_Module_Processor_UserFeedbackMessageAlertLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_USERAVATAR_UPDATE],
        );

        if ($layout = $layouts[$component->name] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}



