<?php

class PoPTheme_AAL_LayoutHooks
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
        $layouts[] = [PopThemeWassup_AAL_Module_Processor_BackgroundColorStyleLayouts::class, PopThemeWassup_AAL_Module_Processor_BackgroundColorStyleLayouts::MODULE_LAYOUT_MARKNOTIFICATIONASREAD_TOPBGCOLORSTYLES];
        return $layouts;
    }

    public function markasunreadLayouts($layouts)
    {
        $layouts[] = [PopThemeWassup_AAL_Module_Processor_BackgroundColorStyleLayouts::class, PopThemeWassup_AAL_Module_Processor_BackgroundColorStyleLayouts::MODULE_LAYOUT_MARKNOTIFICATIONASUNREAD_TOPBGCOLORSTYLES];
        return $layouts;
    }
}

/**
 * Initialization
 */
new PoPTheme_AAL_LayoutHooks();
