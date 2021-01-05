<?php

class PoP_UserAvatarProcessors_Module_Processor_UserFeedbackMessages extends PoP_Module_Processor_FeedbackMessagesBase
{
    public const MODULE_FEEDBACKMESSAGE_USERAVATAR_UPDATE = 'feedbackmessage-useravatar-update';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FEEDBACKMESSAGE_USERAVATAR_UPDATE],
        );
    }

    public function getInnerSubmodule(array $module)
    {
        $inners = array(
            self::MODULE_FEEDBACKMESSAGE_USERAVATAR_UPDATE => [PoP_UserAvatarProcessors_Module_Processor_UserFeedbackMessageInners::class, PoP_UserAvatarProcessors_Module_Processor_UserFeedbackMessageInners::MODULE_FEEDBACKMESSAGEINNER_USERAVATAR_UPDATE],
        );

        if ($inner = $inners[$module[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($module);
    }
}



