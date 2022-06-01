<?php

class GD_UserLogin_Module_Processor_UserFeedbackMessageInners extends PoP_Module_Processor_ActionExecutionFeedbackMessageInnersBase
{
    public final const COMPONENT_FEEDBACKMESSAGEINNER_LOGIN = 'feedbackmessageinner-login';
    public final const COMPONENT_FEEDBACKMESSAGEINNER_LOSTPWD = 'feedbackmessageinner-lostpwd';
    public final const COMPONENT_FEEDBACKMESSAGEINNER_LOSTPWDRESET = 'feedbackmessageinner-lostpwdreset';
    public final const COMPONENT_FEEDBACKMESSAGEINNER_LOGOUT = 'feedbackmessageinner-logout';
    public final const COMPONENT_FEEDBACKMESSAGEINNER_USER_CHANGEPASSWORD = 'feedbackmessageinner-user-changepassword';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FEEDBACKMESSAGEINNER_LOGIN],
            [self::class, self::COMPONENT_FEEDBACKMESSAGEINNER_LOSTPWD],
            [self::class, self::COMPONENT_FEEDBACKMESSAGEINNER_LOSTPWDRESET],
            [self::class, self::COMPONENT_FEEDBACKMESSAGEINNER_LOGOUT],
            [self::class, self::COMPONENT_FEEDBACKMESSAGEINNER_USER_CHANGEPASSWORD],
        );
    }

    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getLayoutSubcomponents($component);

        $layouts = array(
            self::COMPONENT_FEEDBACKMESSAGEINNER_LOGIN => [GD_UserLogin_Module_Processor_UserFeedbackMessageAlertLayouts::class, GD_UserLogin_Module_Processor_UserFeedbackMessageAlertLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_LOGIN],
            self::COMPONENT_FEEDBACKMESSAGEINNER_LOSTPWD => [GD_UserLogin_Module_Processor_UserFeedbackMessageAlertLayouts::class, GD_UserLogin_Module_Processor_UserFeedbackMessageAlertLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_LOSTPWD],
            self::COMPONENT_FEEDBACKMESSAGEINNER_LOSTPWDRESET => [GD_UserLogin_Module_Processor_UserFeedbackMessageAlertLayouts::class, GD_UserLogin_Module_Processor_UserFeedbackMessageAlertLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_LOSTPWDRESET],
            self::COMPONENT_FEEDBACKMESSAGEINNER_LOGOUT => [GD_UserLogin_Module_Processor_UserFeedbackMessageAlertLayouts::class, GD_UserLogin_Module_Processor_UserFeedbackMessageAlertLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_LOGOUT],
            self::COMPONENT_FEEDBACKMESSAGEINNER_USER_CHANGEPASSWORD => [GD_UserLogin_Module_Processor_UserFeedbackMessageAlertLayouts::class, GD_UserLogin_Module_Processor_UserFeedbackMessageAlertLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_USER_CHANGEPASSWORD],
        );

        if ($layout = $layouts[$component[1]] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}



