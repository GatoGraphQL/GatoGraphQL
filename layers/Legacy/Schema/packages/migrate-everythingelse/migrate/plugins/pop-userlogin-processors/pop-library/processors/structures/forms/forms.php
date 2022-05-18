<?php
use PoP\ComponentModel\Misc\RequestUtils;
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_UserLogin_Module_Processor_UserForms extends PoP_Module_Processor_FormsBase
{
    public final const MODULE_FORM_LOGIN = 'form-login';
    public final const MODULE_FORM_LOSTPWD = 'form-lostpwd';
    public final const MODULE_FORM_LOSTPWDRESET = 'form-lostpwdreset';
    public final const MODULE_FORM_LOGOUT = 'form-logout';
    public final const MODULE_FORM_USER_CHANGEPASSWORD = 'form-user-changepwd';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORM_LOGIN],
            [self::class, self::COMPONENT_FORM_LOSTPWD],
            [self::class, self::COMPONENT_FORM_LOSTPWDRESET],
            [self::class, self::COMPONENT_FORM_LOGOUT],
            [self::class, self::COMPONENT_FORM_USER_CHANGEPASSWORD],
        );
    }

    public function getInnerSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORM_LOGIN:
                return [GD_UserLogin_Module_Processor_UserFormInners::class, GD_UserLogin_Module_Processor_UserFormInners::COMPONENT_FORMINNER_LOGIN];

            case self::COMPONENT_FORM_LOSTPWD:
                return [GD_UserLogin_Module_Processor_UserFormInners::class, GD_UserLogin_Module_Processor_UserFormInners::COMPONENT_FORMINNER_LOSTPWD];

            case self::COMPONENT_FORM_LOSTPWDRESET:
                return [GD_UserLogin_Module_Processor_UserFormInners::class, GD_UserLogin_Module_Processor_UserFormInners::COMPONENT_FORMINNER_LOSTPWDRESET];

            case self::COMPONENT_FORM_LOGOUT:
                return [GD_UserLogin_Module_Processor_UserFormInners::class, GD_UserLogin_Module_Processor_UserFormInners::COMPONENT_FORMINNER_LOGOUT];

            case self::COMPONENT_FORM_USER_CHANGEPASSWORD:
                return [GD_UserLogin_Module_Processor_UserFormInners::class, GD_UserLogin_Module_Processor_UserFormInners::COMPONENT_FORMINNER_USER_CHANGEPASSWORD];
        }

        return parent::getInnerSubmodule($component);
    }

    public function getJsmethods(array $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_FORM_LOGIN:
            case self::COMPONENT_FORM_LOGOUT:
                $this->addJsmethod($ret, 'addDomainClass');
                break;
        }

        return $ret;
    }
    public function getImmutableJsconfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableJsconfiguration($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_FORM_LOGIN:
                // For function addDomainClass
                $ret['addDomainClass']['prefix'] = 'visible-notloggedin-';
                break;

            case self::COMPONENT_FORM_LOGOUT:
                // For function addDomainClass
                $ret['addDomainClass']['prefix'] = 'visible-loggedin-';
                break;
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        switch ($component[1]) {
            case self::COMPONENT_FORM_LOGIN:
                $description = sprintf(
                    '<div class="pull-right"><p><em><a href="%s">%s</a></em></p></div>',
                    RouteUtils::getRouteURL(POP_USERLOGIN_ROUTE_LOSTPWD),
                    RouteUtils::getRouteTitle(POP_USERLOGIN_ROUTE_LOSTPWD)
                );
                $this->setProp($component, $props, 'description-bottom', $description);

                // Do not show if user already logged in
                $this->appendProp($component, $props, 'class', 'visible-notloggedin');
                break;

            case self::COMPONENT_FORM_LOSTPWD:
                $description = sprintf(
                    '<p class="bg-info text-info"><em>%s</em></p>',
                    TranslationAPIFacade::getInstance()->__('Please type in your account username or email:', 'pop-coreprocessors')
                );
                $description_bottom = sprintf(
                    '<div class="pull-right"><p><em><a href="%s">%s</a></em></p></div>',
                    RouteUtils::getRouteURL(POP_USERLOGIN_ROUTE_LOGIN),
                    RouteUtils::getRouteTitle(POP_USERLOGIN_ROUTE_LOGIN)
                );
                $this->setProp($component, $props, 'description', $description);
                $this->setProp($component, $props, 'description-bottom', $description_bottom);
                break;

            case self::COMPONENT_FORM_LOSTPWDRESET:
                // There are 2 ways of reaching this form:
                // 1. After the user clicks on reset password, will be redirected
                // 2. After the user clicks on the link on the sent email
                // For these 2 cases, the message is different
                if (RequestUtils::loadingSite()) {
                    $body = TranslationAPIFacade::getInstance()->__('Please choose your new password.', 'pop-coreprocessors');
                } else {
                    $body = sprintf(
                        '<strong>%s</strong> %s',
                        TranslationAPIFacade::getInstance()->__('We sent you an email.', 'pop-coreprocessors'),
                        TranslationAPIFacade::getInstance()->__('Please paste the included code in the input below, and choose your new password.', 'pop-coreprocessors')
                    );
                }
                $description = sprintf(
                    '<p class="bg-info text-info">%s</p>',
                    $body
                );
                $this->setProp($component, $props, 'description', $description);
                break;

            case self::COMPONENT_FORM_LOGOUT:
                $this->appendProp($component, $props, 'class', 'visible-loggedin');

                // Add the description
                $description = sprintf(
                    '<p><em>%s</em></p>',
                    TranslationAPIFacade::getInstance()->__('Are you sure you want to log out?', 'pop-coreprocessors')
                );
                $this->setProp($component, $props, 'description', $description);
                break;
        }

        parent::initModelProps($component, $props);
    }
}



