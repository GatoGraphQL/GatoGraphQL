<?php
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_UserLogin_Module_Processor_UserFeedbackMessageLayouts extends PoP_Module_Processor_FormFeedbackMessageLayoutsBase
{
    public final const MODULE_LAYOUT_FEEDBACKMESSAGE_LOGIN = 'layout-feedbackmessage-login';
    public final const MODULE_LAYOUT_FEEDBACKMESSAGE_LOSTPWD = 'layout-feedbackmessage-lostpwd';
    public final const MODULE_LAYOUT_FEEDBACKMESSAGE_LOSTPWDRESET = 'layout-feedbackmessage-lostpwdreset';
    public final const MODULE_LAYOUT_FEEDBACKMESSAGE_LOGOUT = 'layout-feedbackmessage-logout';
    public final const MODULE_LAYOUT_FEEDBACKMESSAGE_USER_CHANGEPASSWORD = 'layout-feedbackmessage-user-changepassword';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_FEEDBACKMESSAGE_LOGIN],
            [self::class, self::MODULE_LAYOUT_FEEDBACKMESSAGE_LOSTPWD],
            [self::class, self::MODULE_LAYOUT_FEEDBACKMESSAGE_LOSTPWDRESET],
            [self::class, self::MODULE_LAYOUT_FEEDBACKMESSAGE_LOGOUT],
            [self::class, self::MODULE_LAYOUT_FEEDBACKMESSAGE_USER_CHANGEPASSWORD],
        );
    }

    public function getMessages(array $component, array &$props)
    {
        $ret = parent::getMessages($component, $props);

        $cmsuseraccountapi = \PoP\UserAccount\FunctionAPIFactory::getInstance();
        switch ($component[1]) {
            case self::MODULE_LAYOUT_FEEDBACKMESSAGE_LOGIN:
                $ret['success-header'] = TranslationAPIFacade::getInstance()->__('Hurray, login successful!', 'pop-coreprocessors');
                $addnew = '<i class="fa fa-fw fa-plus"></i>'.TranslationAPIFacade::getInstance()->__('Add', 'pop-coreprocessors');
                $ret['success'] = TranslationAPIFacade::getInstance()->__('You can now add posts and comments, follow users, etc', 'pop-coreprocessors');
                break;

            case self::MODULE_LAYOUT_FEEDBACKMESSAGE_LOSTPWD:
                $ret['success-header'] = TranslationAPIFacade::getInstance()->__('Almost there...', 'pop-coreprocessors');
                $ret['success'] = sprintf(
                    TranslationAPIFacade::getInstance()->__('We sent you an email with a code. Please copy it and <a href="%s">paste it here</a>.', 'pop-coreprocessors'),
                    RouteUtils::getRouteURL(POP_USERLOGIN_ROUTE_LOSTPWDRESET)
                );
                break;

            case self::MODULE_LAYOUT_FEEDBACKMESSAGE_LOSTPWDRESET:
                $ret['success-header'] = TranslationAPIFacade::getInstance()->__('Password reset successful', 'pop-coreprocessors');
                $ret['success'] = sprintf(
                    TranslationAPIFacade::getInstance()->__('Please <a href="%s">click here to log in</a>.', 'pop-coreprocessors'),
                    $cmsuseraccountapi->getLoginURL()
                );
                $ret['error-nocode'] = TranslationAPIFacade::getInstance()->__('The code is missing', 'pop-coreprocessors');
                $ret['error-wrongcode'] = TranslationAPIFacade::getInstance()->__('The code is not correct', 'pop-coreprocessors');
                $ret['error-nopwd'] = TranslationAPIFacade::getInstance()->__('Password is missing', 'pop-coreprocessors');
                $ret['error-short'] = TranslationAPIFacade::getInstance()->__('The password must be at least 8 characters long.', 'pop-coreprocessors');
                $ret['error-norepeatpwd'] = TranslationAPIFacade::getInstance()->__('Repeat password is missing', 'pop-coreprocessors');
                $ret['error-pwdnomatch'] = TranslationAPIFacade::getInstance()->__('The passwords do not match', 'pop-coreprocessors');
                $ret['error-invalidkey'] = sprintf(
                    TranslationAPIFacade::getInstance()->__('The code is invalid or expired. Please <a href="%s">generate it again</a>.', 'pop-coreprocessors'),
                    $cmsuseraccountapi->getLostPasswordURL()
                );
                break;

            case self::MODULE_LAYOUT_FEEDBACKMESSAGE_LOGOUT:
                $ret['success-header'] = TranslationAPIFacade::getInstance()->__('Logged out now', 'pop-coreprocessors');
                $ret['success'] = TranslationAPIFacade::getInstance()->__('But please make sure to come back!', 'pop-coreprocessors');
                break;

            case self::MODULE_LAYOUT_FEEDBACKMESSAGE_USER_CHANGEPASSWORD:
                $ret['success-header'] = TranslationAPIFacade::getInstance()->__('Password updated successfully.', 'pop-coreprocessors');
                $ret['success'] = TranslationAPIFacade::getInstance()->__('Yep, it\'s good to change it every now and then, with so many crooks around!', 'pop-coreprocessors');
                break;
        }

        return $ret;
    }
}



