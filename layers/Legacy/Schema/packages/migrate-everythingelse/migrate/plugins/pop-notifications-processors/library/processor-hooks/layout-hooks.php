<?php

class PoP_Notifications_LayoutHooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'GD_AAL_Module_Processor_FunctionsContentMultipleInners:markasread:layouts',
            $this->markasreadLayouts(...)
        );
        \PoP\Root\App::addFilter(
            'GD_AAL_Module_Processor_FunctionsContentMultipleInners:markasunread:layouts',
            $this->markasunreadLayouts(...)
        );
    }

    public function markasreadLayouts($layouts)
    {
        $layouts[] = [Pop_Notifications_Module_Processor_BackgroundColorStyleLayouts::class, Pop_Notifications_Module_Processor_BackgroundColorStyleLayouts::COMPONENT_LAYOUT_MARKNOTIFICATIONASREAD_BGCOLORSTYLES];
        return $layouts;
    }

    public function markasunreadLayouts($layouts)
    {
        $layouts[] = [Pop_Notifications_Module_Processor_BackgroundColorStyleLayouts::class, Pop_Notifications_Module_Processor_BackgroundColorStyleLayouts::COMPONENT_LAYOUT_MARKNOTIFICATIONASUNREAD_BGCOLORSTYLES];
        return $layouts;
    }
}

/**
 * Initialization
 */
new PoP_Notifications_LayoutHooks();
