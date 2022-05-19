<?php

class GD_UserLogin_Module_Processor_UserFeedbackMessageAlertLayouts extends PoP_Module_Processor_FeedbackMessageAlertLayoutsBase
{
    public final const COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_LOGIN = 'layout-feedbackmessagealert-login';
    public final const COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_LOSTPWD = 'layout-feedbackmessagealert-lostpwd';
    public final const COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_LOSTPWDRESET = 'layout-feedbackmessagealert-lostpwdreset';
    public final const COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_LOGOUT = 'layout-feedbackmessagealert-logout';
    public final const COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_USER_CHANGEPASSWORD = 'layout-feedbackmessagealert-user-changepassword';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_LOGIN],
            [self::class, self::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_LOSTPWD],
            [self::class, self::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_LOSTPWDRESET],
            [self::class, self::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_LOGOUT],
            [self::class, self::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_USER_CHANGEPASSWORD],
        );
    }

    public function getLayoutSubcomponent(array $component)
    {
        $layouts = array(
            self::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_LOGIN => [GD_UserLogin_Module_Processor_UserFeedbackMessageLayouts::class, GD_UserLogin_Module_Processor_UserFeedbackMessageLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGE_LOGIN],
            self::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_LOSTPWD => [GD_UserLogin_Module_Processor_UserFeedbackMessageLayouts::class, GD_UserLogin_Module_Processor_UserFeedbackMessageLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGE_LOSTPWD],
            self::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_LOSTPWDRESET => [GD_UserLogin_Module_Processor_UserFeedbackMessageLayouts::class, GD_UserLogin_Module_Processor_UserFeedbackMessageLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGE_LOSTPWDRESET],
            self::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_LOGOUT => [GD_UserLogin_Module_Processor_UserFeedbackMessageLayouts::class, GD_UserLogin_Module_Processor_UserFeedbackMessageLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGE_LOGOUT],
            self::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_USER_CHANGEPASSWORD => [GD_UserLogin_Module_Processor_UserFeedbackMessageLayouts::class, GD_UserLogin_Module_Processor_UserFeedbackMessageLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGE_USER_CHANGEPASSWORD],
        );

        if ($layout = $layouts[$component[1]] ?? null) {
            return $layout;
        }

        return parent::getLayoutSubcomponent($component);
    }
}



