<?php

class PoP_CommonAutomatedEmails_AAL_ScreenHooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_Application_Utils:defaultformat_by_screen',
            array($this, 'getDefaultformatByScreen'),
            0,
            2
        );
    }

    public function getDefaultformatByScreen($format, $screen)
    {
        switch ($screen) {
            case POP_AUTOMATEDEMAIL_SCREEN_NOTIFICATIONS:
                return POP_FORMAT_LIST;
        }

        return $format;
    }
}

/**
 * Initialization
 */
new PoP_CommonAutomatedEmails_AAL_ScreenHooks();
