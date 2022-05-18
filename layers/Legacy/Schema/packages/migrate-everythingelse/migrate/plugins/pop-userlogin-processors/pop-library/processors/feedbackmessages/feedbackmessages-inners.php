<?php

class GD_UserLogin_Module_Processor_UserFeedbackMessageInners extends PoP_Module_Processor_ActionExecutionFeedbackMessageInnersBase
{
    public final const MODULE_FEEDBACKMESSAGEINNER_LOGIN = 'feedbackmessageinner-login';
    public final const MODULE_FEEDBACKMESSAGEINNER_LOSTPWD = 'feedbackmessageinner-lostpwd';
    public final const MODULE_FEEDBACKMESSAGEINNER_LOSTPWDRESET = 'feedbackmessageinner-lostpwdreset';
    public final const MODULE_FEEDBACKMESSAGEINNER_LOGOUT = 'feedbackmessageinner-logout';
    public final const MODULE_FEEDBACKMESSAGEINNER_USER_CHANGEPASSWORD = 'feedbackmessageinner-user-changepassword';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FEEDBACKMESSAGEINNER_LOGIN],
            [self::class, self::MODULE_FEEDBACKMESSAGEINNER_LOSTPWD],
            [self::class, self::MODULE_FEEDBACKMESSAGEINNER_LOSTPWDRESET],
            [self::class, self::MODULE_FEEDBACKMESSAGEINNER_LOGOUT],
            [self::class, self::MODULE_FEEDBACKMESSAGEINNER_USER_CHANGEPASSWORD],
        );
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        $layouts = array(
            self::MODULE_FEEDBACKMESSAGEINNER_LOGIN => [GD_UserLogin_Module_Processor_UserFeedbackMessageAlertLayouts::class, GD_UserLogin_Module_Processor_UserFeedbackMessageAlertLayouts::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_LOGIN],
            self::MODULE_FEEDBACKMESSAGEINNER_LOSTPWD => [GD_UserLogin_Module_Processor_UserFeedbackMessageAlertLayouts::class, GD_UserLogin_Module_Processor_UserFeedbackMessageAlertLayouts::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_LOSTPWD],
            self::MODULE_FEEDBACKMESSAGEINNER_LOSTPWDRESET => [GD_UserLogin_Module_Processor_UserFeedbackMessageAlertLayouts::class, GD_UserLogin_Module_Processor_UserFeedbackMessageAlertLayouts::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_LOSTPWDRESET],
            self::MODULE_FEEDBACKMESSAGEINNER_LOGOUT => [GD_UserLogin_Module_Processor_UserFeedbackMessageAlertLayouts::class, GD_UserLogin_Module_Processor_UserFeedbackMessageAlertLayouts::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_LOGOUT],
            self::MODULE_FEEDBACKMESSAGEINNER_USER_CHANGEPASSWORD => [GD_UserLogin_Module_Processor_UserFeedbackMessageAlertLayouts::class, GD_UserLogin_Module_Processor_UserFeedbackMessageAlertLayouts::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_USER_CHANGEPASSWORD],
        );

        if ($layout = $layouts[$component[1]] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}



