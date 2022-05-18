<?php

class PoP_UserAvatarProcessors_Module_Processor_UserFeedbackMessages extends PoP_Module_Processor_FeedbackMessagesBase
{
    public final const COMPONENT_FEEDBACKMESSAGE_USERAVATAR_UPDATE = 'feedbackmessage-useravatar-update';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FEEDBACKMESSAGE_USERAVATAR_UPDATE],
        );
    }

    public function getInnerSubmodule(array $component)
    {
        $inners = array(
            self::COMPONENT_FEEDBACKMESSAGE_USERAVATAR_UPDATE => [PoP_UserAvatarProcessors_Module_Processor_UserFeedbackMessageInners::class, PoP_UserAvatarProcessors_Module_Processor_UserFeedbackMessageInners::COMPONENT_FEEDBACKMESSAGEINNER_USERAVATAR_UPDATE],
        );

        if ($inner = $inners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($component);
    }
}



