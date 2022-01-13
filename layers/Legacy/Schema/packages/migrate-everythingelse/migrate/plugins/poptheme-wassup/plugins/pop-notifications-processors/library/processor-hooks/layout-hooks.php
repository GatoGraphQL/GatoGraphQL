<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoPTheme_AAL_LayoutHooks
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
