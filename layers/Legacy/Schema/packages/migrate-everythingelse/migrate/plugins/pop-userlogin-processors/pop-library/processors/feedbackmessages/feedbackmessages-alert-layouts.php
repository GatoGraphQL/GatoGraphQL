<?php

class GD_UserLogin_Module_Processor_UserFeedbackMessageAlertLayouts extends PoP_Module_Processor_FeedbackMessageAlertLayoutsBase
{
    public final const MODULE_LAYOUT_FEEDBACKMESSAGEALERT_LOGIN = 'layout-feedbackmessagealert-login';
    public final const MODULE_LAYOUT_FEEDBACKMESSAGEALERT_LOSTPWD = 'layout-feedbackmessagealert-lostpwd';
    public final const MODULE_LAYOUT_FEEDBACKMESSAGEALERT_LOSTPWDRESET = 'layout-feedbackmessagealert-lostpwdreset';
    public final const MODULE_LAYOUT_FEEDBACKMESSAGEALERT_LOGOUT = 'layout-feedbackmessagealert-logout';
    public final const MODULE_LAYOUT_FEEDBACKMESSAGEALERT_USER_CHANGEPASSWORD = 'layout-feedbackmessagealert-user-changepassword';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_LOGIN],
            [self::class, self::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_LOSTPWD],
            [self::class, self::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_LOSTPWDRESET],
            [self::class, self::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_LOGOUT],
            [self::class, self::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_USER_CHANGEPASSWORD],
        );
    }

    public function getLayoutSubmodule(array $module)
    {
        $layouts = array(
            self::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_LOGIN => [GD_UserLogin_Module_Processor_UserFeedbackMessageLayouts::class, GD_UserLogin_Module_Processor_UserFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_LOGIN],
            self::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_LOSTPWD => [GD_UserLogin_Module_Processor_UserFeedbackMessageLayouts::class, GD_UserLogin_Module_Processor_UserFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_LOSTPWD],
            self::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_LOSTPWDRESET => [GD_UserLogin_Module_Processor_UserFeedbackMessageLayouts::class, GD_UserLogin_Module_Processor_UserFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_LOSTPWDRESET],
            self::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_LOGOUT => [GD_UserLogin_Module_Processor_UserFeedbackMessageLayouts::class, GD_UserLogin_Module_Processor_UserFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_LOGOUT],
            self::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_USER_CHANGEPASSWORD => [GD_UserLogin_Module_Processor_UserFeedbackMessageLayouts::class, GD_UserLogin_Module_Processor_UserFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_USER_CHANGEPASSWORD],
        );

        if ($layout = $layouts[$module[1]] ?? null) {
            return $layout;
        }

        return parent::getLayoutSubmodule($module);
    }
}



