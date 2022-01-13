<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_LocationPosts_Module_Processor_CustomScrollInners extends PoP_Module_Processor_ScrollInnersBase
{
    public const MODULE_SCROLLINNER_LOCATIONPOSTS_MAP = 'scrollinner-locationposts-map';
    public const MODULE_SCROLLINNER_LOCATIONPOSTS_HORIZONTALMAP = 'scrollinner-locationposts-horizontalmap';
    public const MODULE_SCROLLINNER_LOCATIONPOSTS_NAVIGATOR = 'scrollinner-locationposts-navigator';
    public const MODULE_SCROLLINNER_LOCATIONPOSTS_ADDONS = 'scrollinner-locationposts-addons';
    public const MODULE_SCROLLINNER_LOCATIONPOSTS_DETAILS = 'scrollinner-locationposts-details';
    public const MODULE_SCROLLINNER_LOCATIONPOSTS_SIMPLEVIEW = 'scrollinner-locationposts-simpleview';
    public const MODULE_SCROLLINNER_LOCATIONPOSTS_FULLVIEW = 'scrollinner-locationposts-fullview';
    public const MODULE_SCROLLINNER_AUTHORLOCATIONPOSTS_FULLVIEW = 'scrollinner-authorlocationposts-fullview';
    public const MODULE_SCROLLINNER_LOCATIONPOSTS_THUMBNAIL = 'scrollinner-locationposts-thumbnail';
    public const MODULE_SCROLLINNER_LOCATIONPOSTS_LIST = 'scrollinner-locationposts-list';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SCROLLINNER_LOCATIONPOSTS_MAP],
            [self::class, self::MODULE_SCROLLINNER_LOCATIONPOSTS_HORIZONTALMAP],
            [self::class, self::MODULE_SCROLLINNER_LOCATIONPOSTS_NAVIGATOR],
            [self::class, self::MODULE_SCROLLINNER_LOCATIONPOSTS_ADDONS],
            [self::class, self::MODULE_SCROLLINNER_LOCATIONPOSTS_DETAILS],
            [self::class, self::MODULE_SCROLLINNER_LOCATIONPOSTS_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLLINNER_LOCATIONPOSTS_FULLVIEW],
            [self::class, self::MODULE_SCROLLINNER_LOCATIONPOSTS_THUMBNAIL],
            [self::class, self::MODULE_SCROLLINNER_LOCATIONPOSTS_LIST],
            [self::class, self::MODULE_SCROLLINNER_AUTHORLOCATIONPOSTS_FULLVIEW],
        );
    }

    public function getLayoutGrid(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_SCROLLINNER_LOCATIONPOSTS_THUMBNAIL:
                // Allow ThemeStyle Expansive to override the grid
                return HooksAPIFacade::getInstance()->applyFilters(
                    POP_HOOK_SCROLLINNER_THUMBNAIL_GRID,
                    array(
                        'row-items' => 2,
                        'class' => 'col-xsm-6'
                    )
                );

            case self::MODULE_SCROLLINNER_LOCATIONPOSTS_MAP:
            case self::MODULE_SCROLLINNER_LOCATIONPOSTS_HORIZONTALMAP:
            case self::MODULE_SCROLLINNER_LOCATIONPOSTS_NAVIGATOR:
            case self::MODULE_SCROLLINNER_LOCATIONPOSTS_ADDONS:
            case self::MODULE_SCROLLINNER_LOCATIONPOSTS_DETAILS:
            case self::MODULE_SCROLLINNER_LOCATIONPOSTS_SIMPLEVIEW:
            case self::MODULE_SCROLLINNER_LOCATIONPOSTS_FULLVIEW:
            case self::MODULE_SCROLLINNER_LOCATIONPOSTS_LIST:
            case self::MODULE_SCROLLINNER_AUTHORLOCATIONPOSTS_FULLVIEW:
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
            self::MODULE_SCROLLINNER_LOCATIONPOSTS_MAP => [GD_Custom_EM_Module_Processor_CustomPreviewPostLayouts::class, GD_Custom_EM_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_MAPDETAILS],
            self::MODULE_SCROLLINNER_LOCATIONPOSTS_HORIZONTALMAP => [GD_Custom_EM_Module_Processor_CustomPreviewPostLayouts::class, GD_Custom_EM_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_HORIZONTALMAPDETAILS],
            self::MODULE_SCROLLINNER_LOCATIONPOSTS_NAVIGATOR => [GD_Custom_EM_Module_Processor_CustomPreviewPostLayouts::class, GD_Custom_EM_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_NAVIGATOR],
            self::MODULE_SCROLLINNER_LOCATIONPOSTS_ADDONS => [GD_Custom_EM_Module_Processor_CustomPreviewPostLayouts::class, GD_Custom_EM_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_ADDONS],
            self::MODULE_SCROLLINNER_LOCATIONPOSTS_DETAILS => [GD_Custom_EM_Module_Processor_CustomPreviewPostLayouts::class, GD_Custom_EM_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_DETAILS],
            self::MODULE_SCROLLINNER_LOCATIONPOSTS_THUMBNAIL => [GD_Custom_EM_Module_Processor_CustomPreviewPostLayouts::class, GD_Custom_EM_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_THUMBNAIL],
            self::MODULE_SCROLLINNER_LOCATIONPOSTS_LIST => [GD_Custom_EM_Module_Processor_CustomPreviewPostLayouts::class, GD_Custom_EM_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_LIST],
            self::MODULE_SCROLLINNER_LOCATIONPOSTS_SIMPLEVIEW => [PoPSFEM_Module_Processor_SimpleViewPreviewPostLayouts::class, PoPSFEM_Module_Processor_SimpleViewPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_LOCATIONPOST_SIMPLEVIEW],
            self::MODULE_SCROLLINNER_LOCATIONPOSTS_FULLVIEW => [GD_Custom_EM_Module_Processor_CustomFullViewLayouts::class, GD_Custom_EM_Module_Processor_CustomFullViewLayouts::MODULE_LAYOUT_FULLVIEW_LOCATIONPOST],
            self::MODULE_SCROLLINNER_AUTHORLOCATIONPOSTS_FULLVIEW => [GD_Custom_EM_Module_Processor_CustomFullViewLayouts::class, GD_Custom_EM_Module_Processor_CustomFullViewLayouts::MODULE_LAYOUT_FULLVIEW_LOCATIONPOST],
        );

        if ($layout = $layouts[$module[1]] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}


