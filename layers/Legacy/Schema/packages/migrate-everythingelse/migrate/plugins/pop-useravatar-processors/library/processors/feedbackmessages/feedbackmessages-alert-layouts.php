<?php

class PoP_UserAvatarProcessors_Module_Processor_UserFeedbackMessageAlertLayouts extends PoP_Module_Processor_FeedbackMessageAlertLayoutsBase
{
    public final const COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_USERAVATAR_UPDATE = 'layout-feedbackmessagealert-useravatar-update';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_USERAVATAR_UPDATE],
        );
    }

    public function getLayoutSubcomponent(array $component)
    {
        $layouts = array(
            self::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_USERAVATAR_UPDATE => [PoP_UserAvatarProcessors_Module_Processor_UserFeedbackMessageLayouts::class, PoP_UserAvatarProcessors_Module_Processor_UserFeedbackMessageLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGE_USERAVATAR_UPDATE],
        );

        if ($layout = $layouts[$component[1]] ?? null) {
            return $layout;
        }

        return parent::getLayoutSubcomponent($component);
    }
}



