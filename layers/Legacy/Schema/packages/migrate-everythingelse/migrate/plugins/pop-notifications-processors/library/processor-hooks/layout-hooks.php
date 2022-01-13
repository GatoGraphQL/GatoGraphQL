<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_Notifications_LayoutHooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addFilter(
            'GD_AAL_Module_Processor_FunctionsContentMultipleInners:markasread:layouts',
            array($this, 'markasreadLayouts')
        );
        HooksAPIFacade::getInstance()->addFilter(
            'GD_AAL_Module_Processor_FunctionsContentMultipleInners:markasunread:layouts',
            array($this, 'markasunreadLayouts')
        );
    }

    public function markasreadLayouts($layouts)
    {
        $layouts[] = [Pop_Notifications_Module_Processor_BackgroundColorStyleLayouts::class, Pop_Notifications_Module_Processor_BackgroundColorStyleLayouts::MODULE_LAYOUT_MARKNOTIFICATIONASREAD_BGCOLORSTYLES];
        return $layouts;
    }

    public function markasunreadLayouts($layouts)
    {
        $layouts[] = [Pop_Notifications_Module_Processor_BackgroundColorStyleLayouts::class, Pop_Notifications_Module_Processor_BackgroundColorStyleLayouts::MODULE_LAYOUT_MARKNOTIFICATIONASUNREAD_BGCOLORSTYLES];
        return $layouts;
    }
}

/**
 * Initialization
 */
new PoP_Notifications_LayoutHooks();
