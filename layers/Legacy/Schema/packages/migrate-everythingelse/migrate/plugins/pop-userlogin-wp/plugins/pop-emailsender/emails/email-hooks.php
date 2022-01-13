<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_UserLoginWP_WP_EmailSender_Hooks
{
    public function __construct()
    {
        //----------------------------------------------------------------------
        // Functional emails
        //----------------------------------------------------------------------
        HooksAPIFacade::getInstance()->addAction('retrieve_password_key', array($this, 'retrievePasswordKey'));
        HooksAPIFacade::getInstance()->addFilter('send_password_change_email', array($this, 'donotsend'), PHP_INT_MAX, 1);
        HooksAPIFacade::getInstance()->addFilter('send_email_change_email', array($this, 'donotsend'), PHP_INT_MAX, 1);
    }

    public function retrievePasswordKey()
    {
        HooksAPIFacade::getInstance()->addFilter('wp_mail_content_type', array($this, 'setHtmlContentType'));
    }
    public function setHtmlContentType($content_type)
    {
        return 'text/html';
    }

    // Do not send an email when the user changes the password
    public function donotsend($send)
    {
        // Returning in such a weird fashion, because on file wp-includes/user.php from WP 4.3.1 it validates like this:
        // if ( ! empty( $send_email_change_email ) ) {
        return array();
    }
}

/**
 * Initialization
 */
new PoP_UserLoginWP_WP_EmailSender_Hooks();
