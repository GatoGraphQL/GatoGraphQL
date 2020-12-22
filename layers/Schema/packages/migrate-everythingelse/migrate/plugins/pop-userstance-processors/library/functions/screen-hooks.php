<?php
use PoP\Hooks\Facades\HooksAPIFacade;

class PoP_Application_UserStance_ScreenHooks
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
            case POP_USERSTANCE_SCREEN_STANCES:
            case POP_USERSTANCE_SCREEN_AUTHORSTANCES:
            case POP_USERSTANCE_SCREEN_TAGSTANCES:
            case POP_USERSTANCE_SCREEN_SINGLESTANCES:
                return POP_FORMAT_LIST;

            case POP_USERSTANCE_SCREEN_MYSTANCES:
                return POP_FORMAT_TABLE;
        }

        return $format;
    }
}

/**
 * Initialization
 */
new PoP_Application_UserStance_ScreenHooks();
