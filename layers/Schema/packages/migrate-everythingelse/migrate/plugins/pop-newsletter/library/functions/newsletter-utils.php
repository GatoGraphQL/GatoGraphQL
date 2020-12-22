<?php

class PoP_GenericForms_NewsletterUtils
{
    public static function getEmailVerificationcode($email)
    {
        return hash_hmac('sha1', $email, POP_NEWSLETTER_PRIVATEKEYS_NEWSLETTERUNSUBSCRIBE);
    }
}
