<?php
use PoP\Hooks\Facades\HooksAPIFacade;

class PoP_CommonAutomatedEmails_AAL_ScreenHooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addFilter(
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
