<?php
namespace PoP\EmailSender\WP;

class FunctionAPI extends \PoP\EmailSender\FunctionAPI_Base
{
    public function getAdminUserEmail()
    {
        return get_bloginfo('admin_email');
    }
    public function sendEmail($to, $subject, $msg, $headers = '', $attachments = array())
    {
        return wp_mail($to, $subject, $msg, $headers, $attachments);
    }
}

/**
 * Initialize
 */
new FunctionAPI();
