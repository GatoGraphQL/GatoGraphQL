<?php

class GD_UserLogin_Module_Processor_UserFeedbackMessages extends PoP_Module_Processor_FeedbackMessagesBase
{
    public const MODULE_FEEDBACKMESSAGE_LOGIN = 'feedbackmessage-login';
    public const MODULE_FEEDBACKMESSAGE_LOSTPWD = 'feedbackmessage-lostpwd';
    public const MODULE_FEEDBACKMESSAGE_LOSTPWDRESET = 'feedbackmessage-lostpwdreset';
    public const MODULE_FEEDBACKMESSAGE_LOGOUT = 'feedbackmessage-logout';
    public const MODULE_FEEDBACKMESSAGE_USER_CHANGEPASSWORD = 'feedbackmessage-user-changepassword';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FEEDBACKMESSAGE_LOGIN],
            [self::class, self::MODULE_FEEDBACKMESSAGE_LOSTPWD],
            [self::class, self::MODULE_FEEDBACKMESSAGE_LOSTPWDRESET],
            [self::class, self::MODULE_FEEDBACKMESSAGE_LOGOUT],
            [self::class, self::MODULE_FEEDBACKMESSAGE_USER_CHANGEPASSWORD],
        );
    }

    public function getInnerSubmodule(array $module)
    {
        $inners = array(
            self::MODULE_FEEDBACKMESSAGE_LOGIN => [GD_UserLogin_Module_Processor_UserFeedbackMessageInners::class, GD_UserLogin_Module_Processor_UserFeedbackMessageInners::MODULE_FEEDBACKMESSAGEINNER_LOGIN],
            self::MODULE_FEEDBACKMESSAGE_LOSTPWD => [GD_UserLogin_Module_Processor_UserFeedbackMessageInners::class, GD_UserLogin_Module_Processor_UserFeedbackMessageInners::MODULE_FEEDBACKMESSAGEINNER_LOSTPWD],
            self::MODULE_FEEDBACKMESSAGE_LOSTPWDRESET => [GD_UserLogin_Module_Processor_UserFeedbackMessageInners::class, GD_UserLogin_Module_Processor_UserFeedbackMessageInners::MODULE_FEEDBACKMESSAGEINNER_LOSTPWDRESET],
            self::MODULE_FEEDBACKMESSAGE_LOGOUT => [GD_UserLogin_Module_Processor_UserFeedbackMessageInners::class, GD_UserLogin_Module_Processor_UserFeedbackMessageInners::MODULE_FEEDBACKMESSAGEINNER_LOGOUT],
            self::MODULE_FEEDBACKMESSAGE_USER_CHANGEPASSWORD => [GD_UserLogin_Module_Processor_UserFeedbackMessageInners::class, GD_UserLogin_Module_Processor_UserFeedbackMessageInners::MODULE_FEEDBACKMESSAGEINNER_USER_CHANGEPASSWORD],
        );

        if ($inner = $inners[$module[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($module);
    }
}



