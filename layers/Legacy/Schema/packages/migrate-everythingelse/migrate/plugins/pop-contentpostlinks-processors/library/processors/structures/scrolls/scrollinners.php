<?php
use PoP\Hooks\Facades\HooksAPIFacade;

class PoP_ContentPostLinks_Module_Processor_CustomScrollInners extends PoP_Module_Processor_ScrollInnersBase
{
    public const MODULE_SCROLLINNER_LINKS_NAVIGATOR = 'scrollinner-links-navigator';
    public const MODULE_SCROLLINNER_LINKS_ADDONS = 'scrollinner-links-addons';
    public const MODULE_SCROLLINNER_LINKS_DETAILS = 'scrollinner-links-details';
    public const MODULE_SCROLLINNER_LINKS_SIMPLEVIEW = 'scrollinner-links-simpleview';
    public const MODULE_SCROLLINNER_LINKS_FULLVIEW = 'scrollinner-links-fullview';
    public const MODULE_SCROLLINNER_AUTHORLINKS_FULLVIEW = 'scrollinner-authorlinks-fullview';
    public const MODULE_SCROLLINNER_LINKS_THUMBNAIL = 'scrollinner-links-thumbnail';
    public const MODULE_SCROLLINNER_LINKS_LIST = 'scrollinner-links-list';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SCROLLINNER_LINKS_NAVIGATOR],
            [self::class, self::MODULE_SCROLLINNER_LINKS_ADDONS],
            [self::class, self::MODULE_SCROLLINNER_LINKS_DETAILS],
            [self::class, self::MODULE_SCROLLINNER_LINKS_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLLINNER_LINKS_FULLVIEW],
            [self::class, self::MODULE_SCROLLINNER_LINKS_THUMBNAIL],
            [self::class, self::MODULE_SCROLLINNER_LINKS_LIST],
            [self::class, self::MODULE_SCROLLINNER_AUTHORLINKS_FULLVIEW],
        );
    }

    public function getLayoutGrid(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_SCROLLINNER_LINKS_THUMBNAIL:
                // Allow ThemeStyle Expansive to override the grid
                return HooksAPIFacade::getInstance()->applyFilters(
                    POP_HOOK_SCROLLINNER_THUMBNAIL_GRID,
                    array(
                        'row-items' => 2,
                        'class' => 'col-xsm-6'
                    )
                );

            case self::MODULE_SCROLLINNER_LINKS_NAVIGATOR:
            case self::MODULE_SCROLLINNER_LINKS_ADDONS:
            case self::MODULE_SCROLLINNER_LINKS_DETAILS:
            case self::MODULE_SCROLLINNER_LINKS_SIMPLEVIEW:
            case self::MODULE_SCROLLINNER_LINKS_FULLVIEW:
            case self::MODULE_SCROLLINNER_LINKS_LIST:
            case self::MODULE_SCROLLINNER_AUTHORLINKS_FULLVIEW:
                return array(
                    'row-items' => 1,
                    'class' => 'col-sm-12'
                );
        }

        return parent::getLayoutGrid($module, $props);
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        $layouts = array(
            self::MODULE_SCROLLINNER_LINKS_NAVIGATOR => [PoP_ContentPostLinks_Module_Processor_CustomPreviewPostLayouts::class, PoP_ContentPostLinks_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_NAVIGATOR],
            self::MODULE_SCROLLINNER_LINKS_ADDONS => [PoP_ContentPostLinks_Module_Processor_CustomPreviewPostLayouts::class, PoP_ContentPostLinks_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_ADDONS],
            self::MODULE_SCROLLINNER_LINKS_DETAILS => [PoP_ContentPostLinks_Module_Processor_CustomPreviewPostLayouts::class, PoP_ContentPostLinks_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_DETAILS],
            self::MODULE_SCROLLINNER_LINKS_THUMBNAIL => [PoP_ContentPostLinks_Module_Processor_CustomPreviewPostLayouts::class, PoP_ContentPostLinks_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_THUMBNAIL],
            self::MODULE_SCROLLINNER_LINKS_LIST => [PoP_ContentPostLinks_Module_Processor_CustomPreviewPostLayouts::class, PoP_ContentPostLinks_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_LIST],
            self::MODULE_SCROLLINNER_LINKS_SIMPLEVIEW => [PoP_Module_Processor_CustomSimpleViewPreviewPostLayouts::class, PoP_Module_Processor_CustomSimpleViewPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_SIMPLEVIEW], //[self::class, self::MODULE_LAYOUT_PREVIEWPOST_CONTENTPOSTLINK_SIMPLEVIEW],
            self::MODULE_SCROLLINNER_LINKS_FULLVIEW => [PoP_ContentPostLinks_Module_Processor_CustomFullViewLayouts::class, PoP_ContentPostLinks_Module_Processor_CustomFullViewLayouts::MODULE_LAYOUT_FULLVIEW_LINK],
            self::MODULE_SCROLLINNER_AUTHORLINKS_FULLVIEW => [PoP_ContentPostLinks_Module_Processor_CustomFullViewLayouts::class, PoP_ContentPostLinks_Module_Processor_CustomFullViewLayouts::MODULE_AUTHORLAYOUT_FULLVIEW_LINK],
        );

        if ($layout = $layouts[$module[1]] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}


