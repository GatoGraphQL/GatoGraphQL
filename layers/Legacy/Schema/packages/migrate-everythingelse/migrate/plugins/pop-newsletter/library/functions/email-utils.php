<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_Newsletter_EmailUtils
{
    public static function getNewsletterEmail()
    {
        $cmsemailsenderapi = \PoP\EmailSender\FunctionAPIFactory::getInstance();

        // By default, use the admin_email, but this can be overriden
        return HooksAPIFacade::getInstance()->applyFilters('gd_email_newsletter_email', $cmsemailsenderapi->getAdminUserEmail());
    }
}
