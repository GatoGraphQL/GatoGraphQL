<?php
use PoP\Hooks\Facades\HooksAPIFacade;

class PoPTheme_Wassup_URE_ScreenHooks
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
            case POP_URE_SCREEN_MYUSERS:
                return POP_FORMAT_TABLE;
        }

        return $format;
    }
}

/**
 * Initialization
 */
new PoPTheme_Wassup_URE_ScreenHooks();
