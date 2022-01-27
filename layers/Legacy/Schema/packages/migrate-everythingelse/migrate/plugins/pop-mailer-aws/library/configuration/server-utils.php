<?php

class PoP_Mailer_AWS_ServerUtils
{
    public static function sendEmailsDisabled()
    {
        return getenv('DISABLE_SENDING_EMAILS_BY_AWS_SES') !== false ? strtolower(getenv('DISABLE_SENDING_EMAILS_BY_AWS_SES')) === "true" : false;
    }
}
