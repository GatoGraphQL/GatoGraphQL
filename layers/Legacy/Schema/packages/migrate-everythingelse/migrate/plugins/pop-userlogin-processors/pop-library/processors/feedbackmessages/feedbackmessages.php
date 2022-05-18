<?php

class GD_UserLogin_Module_Processor_UserFeedbackMessages extends PoP_Module_Processor_FeedbackMessagesBase
{
    public final const MODULE_FEEDBACKMESSAGE_LOGIN = 'feedbackmessage-login';
    public final const MODULE_FEEDBACKMESSAGE_LOSTPWD = 'feedbackmessage-lostpwd';
    public final const MODULE_FEEDBACKMESSAGE_LOSTPWDRESET = 'feedbackmessage-lostpwdreset';
    public final const MODULE_FEEDBACKMESSAGE_LOGOUT = 'feedbackmessage-logout';
    public final const MODULE_FEEDBACKMESSAGE_USER_CHANGEPASSWORD = 'feedbackmessage-user-changepassword';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FEEDBACKMESSAGE_LOGIN],
            [self::class, self::COMPONENT_FEEDBACKMESSAGE_LOSTPWD],
            [self::class, self::COMPONENT_FEEDBACKMESSAGE_LOSTPWDRESET],
            [self::class, self::COMPONENT_FEEDBACKMESSAGE_LOGOUT],
            [self::class, self::COMPONENT_FEEDBACKMESSAGE_USER_CHANGEPASSWORD],
        );
    }

    public function getInnerSubmodule(array $component)
    {
        $inners = array(
            self::COMPONENT_FEEDBACKMESSAGE_LOGIN => [GD_UserLogin_Module_Processor_UserFeedbackMessageInners::class, GD_UserLogin_Module_Processor_UserFeedbackMessageInners::COMPONENT_FEEDBACKMESSAGEINNER_LOGIN],
            self::COMPONENT_FEEDBACKMESSAGE_LOSTPWD => [GD_UserLogin_Module_Processor_UserFeedbackMessageInners::class, GD_UserLogin_Module_Processor_UserFeedbackMessageInners::COMPONENT_FEEDBACKMESSAGEINNER_LOSTPWD],
            self::COMPONENT_FEEDBACKMESSAGE_LOSTPWDRESET => [GD_UserLogin_Module_Processor_UserFeedbackMessageInners::class, GD_UserLogin_Module_Processor_UserFeedbackMessageInners::COMPONENT_FEEDBACKMESSAGEINNER_LOSTPWDRESET],
            self::COMPONENT_FEEDBACKMESSAGE_LOGOUT => [GD_UserLogin_Module_Processor_UserFeedbackMessageInners::class, GD_UserLogin_Module_Processor_UserFeedbackMessageInners::COMPONENT_FEEDBACKMESSAGEINNER_LOGOUT],
            self::COMPONENT_FEEDBACKMESSAGE_USER_CHANGEPASSWORD => [GD_UserLogin_Module_Processor_UserFeedbackMessageInners::class, GD_UserLogin_Module_Processor_UserFeedbackMessageInners::COMPONENT_FEEDBACKMESSAGEINNER_USER_CHANGEPASSWORD],
        );

        if ($inner = $inners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($component);
    }
}



