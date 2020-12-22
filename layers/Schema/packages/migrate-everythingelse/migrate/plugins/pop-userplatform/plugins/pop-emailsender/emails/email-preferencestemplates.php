<?php

define('POP_EMAILFRAME_PREFERENCES', 'preferences');

class PoP_EmailSender_Templates_Preferences extends PoP_EmailSender_Templates_Simple
{
    public function getName()
    {
        return POP_EMAILFRAME_PREFERENCES;
    }

    public function getEmailframeBeforefooter(/*$frame, */$title, $emails, $names, $template)
    {
        return PoP_UserPlatform_EmailSenderUtils::getPreferencesFooter();
    }
}

/**
 * Initialization
 */
new PoP_EmailSender_Templates_Preferences();
