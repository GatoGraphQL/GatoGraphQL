<?php

class PoPTheme_Wassup_EM_ScreenHooks
{
    public function __construct()
    {
        \PoP\Root\App::getHookManager()->addFilter(
            'PoP_Application_Utils:defaultformat_by_screen',
            array($this, 'getDefaultformatByScreen'),
            0,
            2
        );
    }

    public function getDefaultformatByScreen($format, $screen)
    {
        switch ($screen) {
            case POP_SCREEN_SECTIONCALENDAR:
            case POP_SCREEN_AUTHORSECTIONCALENDAR:
            case POP_SCREEN_TAGSECTIONCALENDAR:
                return POP_FORMAT_CALENDAR;
        }

        return $format;
    }
}

/**
 * Initialization
 */
new PoPTheme_Wassup_EM_ScreenHooks();
