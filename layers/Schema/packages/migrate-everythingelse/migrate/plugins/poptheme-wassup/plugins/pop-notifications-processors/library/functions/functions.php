<?php
use PoP\Hooks\Facades\HooksAPIFacade;

HooksAPIFacade::getInstance()->addFilter('PopThemeWassup_AAL_Module_Processor_BackgroundColorStyleLayouts:bgcolor', 'popthemeWassupBgcolor', 10, 2);
function popthemeWassupBgcolor($color, array $module)
{
    switch ($module[1]) {
        case Pop_Notifications_Module_Processor_BackgroundColorStyleLayouts::MODULE_LAYOUT_MARKNOTIFICATIONASREAD_BGCOLORSTYLES:
            return '#fff';

        case Pop_Notifications_Module_Processor_BackgroundColorStyleLayouts::MODULE_LAYOUT_MARKNOTIFICATIONASUNREAD_BGCOLORSTYLES:
            // Same as alert-info background color
            return '#d9edf7';

        case PopThemeWassup_AAL_Module_Processor_BackgroundColorStyleLayouts::MODULE_LAYOUT_MARKNOTIFICATIONASREAD_TOPBGCOLORSTYLES:
            return '#4d4d4f';

        case PopThemeWassup_AAL_Module_Processor_BackgroundColorStyleLayouts::MODULE_LAYOUT_MARKNOTIFICATIONASUNREAD_TOPBGCOLORSTYLES:
            // Same as btn-primary background color
            return '#337ab7';
    }

    return $color;
}
