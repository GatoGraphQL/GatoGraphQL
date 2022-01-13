<?php
use PoP\ComponentModel\State\ApplicationState;
use PoP\Root\Facades\Hooks\HooksAPIFacade;

\PoP\Root\App::getHookManager()->addFilter("gdClassesBody", 'gdWassupThemeBodyClass');
function gdWassupThemeBodyClass($body_classes)
{
    if (\PoP\Root\App::getState('theme') == GD_THEME_WASSUP) {
        $thememode = \PoP\Root\App::getState('thememode');

        // For the 'simple' and 'embed' themes, also add 'sliding' in the body class, since these are sliding implementations and need its css classes
        $addclass_thememodes = array(
            GD_THEMEMODE_WASSUP_SIMPLE,
            GD_THEMEMODE_WASSUP_EMBED,
        );
        if (in_array($thememode, $addclass_thememodes)) {
            $body_classes[] = GD_THEMEMODE_WASSUP_SLIDING;
        }

        if (PoP_ApplicationProcessors_Utils::addMainpagesectionScrollbar()) {
            // Add the offcanvas class when appropriate
            $offcanvas = array(
                GD_THEMEMODE_WASSUP_SIMPLE,
                GD_THEMEMODE_WASSUP_SLIDING,
                GD_THEMEMODE_WASSUP_EMBED,
            );
            if (in_array($thememode, $offcanvas)) {
                $body_classes[] = 'non-scrollable';
            }
        }

        // if (PoP_ApplicationProcessors_Utils::narrowBody()) {

        //     $body_classes[] = 'narrow';
        // }
    }

    return $body_classes;
}
