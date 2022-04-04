<?php
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\Users\Facades\UserTypeAPIFacade;

class PoP_UserPlatform_EmailSender_Hooks
{
    public function __construct()
    {

        //----------------------------------------------------------------------
        // Notifications to the admin
        //----------------------------------------------------------------------
        \PoP\Root\App::addAction('gd_createupdate_profile:additionalsCreate', $this->sendemailToAdminCreateuser(...), 100, 1);
        \PoP\Root\App::addAction('gd_createupdate_profile:additionalsUpdate', $this->sendemailToAdminUpdateuser(...), 100, 1);
    
        //----------------------------------------------------------------------
        // User registration
        //----------------------------------------------------------------------
        \PoP\Root\App::addAction('gd_createupdate_profile:additionalsCreate', $this->sendemailUserwelcome(...), 100, 1);
    }

    /**
     * Send email to admin when user created/updated
     */
    public function sendemailToAdminCreateuser($user_id)
    {

        // Send an email to the admin.
        $this->sendemailToAdminCreateupdateuser($user_id, 'create');
    }
    public function sendemailToAdminUpdateuser($user_id)
    {
        $this->sendemailToAdminCreateupdateuser($user_id, 'update');
    }
    protected function sendemailToAdminCreateupdateuser($user_id, $type)
    {
        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        $userTypeAPI = UserTypeAPIFacade::getInstance();
        $blogname = $cmsapplicationapi->getSiteName();
        $to = PoP_EmailSender_Utils::getAdminNotificationsEmail();
        $permalink = $userTypeAPI->getUserURL($user_id);
        $user_name = $userTypeAPI->getUserDisplayName($user_id);

        if ($type == 'create') {
            $subject = sprintf(TranslationAPIFacade::getInstance()->__('[%s]: New Profile: %s', 'pop-emailsender'), $blogname, $user_name);
            $msg = sprintf(TranslationAPIFacade::getInstance()->__('A Profile was created on %s.', 'pop-emailsender'), $blogname);
        } elseif ($type == 'update') {
            $subject = sprintf(TranslationAPIFacade::getInstance()->__('[%s]: Profile updated: %s', 'pop-emailsender'), $blogname, $user_name);
            $msg = sprintf(TranslationAPIFacade::getInstance()->__('Profile updated on %s.', 'pop-emailsender'), $blogname);
        }

        $msg .= "<br/><br/>";
        $msg .= sprintf(TranslationAPIFacade::getInstance()->__('<b>Profile:</b> %s', 'pop-emailsender'), $user_name) . "<br/>";
        $msg .= sprintf(TranslationAPIFacade::getInstance()->__('<b>Profile link:</b> <a href="%1$s">%1$s</a>', 'pop-emailsender'), $permalink);

        PoP_EmailSender_Utils::sendEmail($to, $subject, $msg);
    }

    /**
     * Send the welcome email to the user
     */
    public function sendemailUserwelcome($user_id)
    {
        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        $blogname = $cmsapplicationapi->getSiteName();
        $subject = sprintf(
            TranslationAPIFacade::getInstance()->__('Welcome to %s!', 'pop-emailsender'),
            $blogname
        );
        $msg = sprintf(
            '<h1>%s</h1>',
            $subject
        );
        $msg .= TranslationAPIFacade::getInstance()->__('<p>Your user account was created successfully. This is your public profile page:</p>', 'pop-emailsender');
        $msg .= PoP_EmailTemplatesFactory::getInstance()->getUserhtml($user_id);

        if ($routes = array_filter(
                \PoP\Root\App::applyFilters(
                    'sendemailUserwelcome:create_routes', 
                    array()
                )
            )) {
            $msg .= sprintf(
                '<br/><p>%s</p>',
                TranslationAPIFacade::getInstance()->__('Now you can share your content/activities with our community:', 'pop-emailsender')
            );
            $msg .= '<ul>';
            foreach ($routes as $createroute) {
                   // Allow values to be false, then don't show
                if ($createroute) {
                    $msg .= sprintf(
                        '<li><a href="%s">%s</a></li>',
                        RouteUtils::getRouteURL($createroute),
                        RouteUtils::getRouteTitle($createroute)
                    );
                }
            }
            $msg .= '</ul>';
        }
        
        $msg .= '<br/>';
        $msg .= sprintf(
            '<h2>%s</h2>',
            sprintf(
                TranslationAPIFacade::getInstance()->__('About %s', 'pop-emailsender'),
                $blogname
            )
        );
        $msg .= sprintf(
            '<p>%s</p>',
            gdGetWebsiteDescription()
        );

        PoP_EmailSender_Utils::sendemailToUser($user_id, $subject, $msg);
    }
}

/**
 * Initialization
 */
new PoP_UserPlatform_EmailSender_Hooks();
