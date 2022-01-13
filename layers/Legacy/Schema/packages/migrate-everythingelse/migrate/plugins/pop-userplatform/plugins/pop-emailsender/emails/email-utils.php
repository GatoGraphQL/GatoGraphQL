<?php
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_UserPlatform_EmailSenderUtils
{
    public static function getPreferencesFooter($msg = '')
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        $text = sprintf(
            TranslationAPIFacade::getInstance()->__('You can edit your preferences for email notifications <a href="%s">here</a>.', 'pop-emailsender'),
            RouteUtils::getRouteURL(POP_USERPLATFORM_ROUTE_MYPREFERENCES)
        );
        return sprintf(
            '<p><small>%s</small></p>',
            $msg ?
            sprintf(
                TranslationAPIFacade::getInstance()->__('%s %s', 'pop-emailsender'),
                $msg,
                $text
            ) : $text
        );
    }
}
