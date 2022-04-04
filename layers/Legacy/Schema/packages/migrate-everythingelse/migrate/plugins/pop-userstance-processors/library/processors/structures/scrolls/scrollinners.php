<?php

class UserStance_Module_Processor_CustomScrollInners extends PoP_Module_Processor_ScrollInnersBase
{
    public final const MODULE_SCROLLINNER_MYSTANCES_FULLVIEWPREVIEW = 'scrollinner-mystance-fullviewpreview';
    public final const MODULE_SCROLLINNER_STANCES_NAVIGATOR = 'scrollinner-stances-navigator';
    public final const MODULE_SCROLLINNER_STANCES_ADDONS = 'scrollinner-stances-addons';
    public final const MODULE_SCROLLINNER_STANCES_FULLVIEW = 'scrollinner-stances-fullview';
    public final const MODULE_SCROLLINNER_STANCES_THUMBNAIL = 'scrollinner-stances-thumbnail';
    public final const MODULE_SCROLLINNER_STANCES_LIST = 'scrollinner-stances-list';
    public final const MODULE_SCROLLINNER_AUTHORSTANCES_FULLVIEW = 'scrollinner-authorstances-fullview';
    public final const MODULE_SCROLLINNER_AUTHORSTANCES_THUMBNAIL = 'scrollinner-authorstances-thumbnail';
    public final const MODULE_SCROLLINNER_AUTHORSTANCES_LIST = 'scrollinner-authorstances-list';
    public final const MODULE_SCROLLINNER_SINGLERELATEDSTANCECONTENT_FULLVIEW = 'scrollinner-singlerelatedstancecontent-fullview';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SCROLLINNER_MYSTANCES_FULLVIEWPREVIEW],
            [self::class, self::MODULE_SCROLLINNER_STANCES_NAVIGATOR],
            [self::class, self::MODULE_SCROLLINNER_STANCES_ADDONS],
            [self::class, self::MODULE_SCROLLINNER_STANCES_FULLVIEW],
            [self::class, self::MODULE_SCROLLINNER_STANCES_THUMBNAIL],
            [self::class, self::MODULE_SCROLLINNER_STANCES_LIST],
            [self::class, self::MODULE_SCROLLINNER_AUTHORSTANCES_FULLVIEW],
            [self::class, self::MODULE_SCROLLINNER_AUTHORSTANCES_THUMBNAIL],
            [self::class, self::MODULE_SCROLLINNER_AUTHORSTANCES_LIST],
            [self::class, self::MODULE_SCROLLINNER_SINGLERELATEDSTANCECONTENT_FULLVIEW],
        );
    }

    public function getLayoutGrid(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_SCROLLINNER_STANCES_THUMBNAIL:
            case self::MODULE_SCROLLINNER_AUTHORSTANCES_THUMBNAIL:
                // Allow ThemeStyle Expansive to override the grid
                return \PoP\Root\App::applyFilters(
                    POP_HOOK_SCROLLINNER_THUMBNAIL_GRID,
                    array(
                        'row-items' => 2,
                        'class' => 'col-xsm-6'
                    )
                );

            case self::MODULE_SCROLLINNER_MYSTANCES_FULLVIEWPREVIEW:
            case self::MODULE_SCROLLINNER_STANCES_NAVIGATOR:
            case self::MODULE_SCROLLINNER_STANCES_ADDONS:
            case self::MODULE_SCROLLINNER_STANCES_FULLVIEW:
            case self::MODULE_SCROLLINNER_STANCES_LIST:
            case self::MODULE_SCROLLINNER_AUTHORSTANCES_FULLVIEW:
            case self::MODULE_SCROLLINNER_AUTHORSTANCES_LIST:
            case self::MODULE_SCROLLINNER_SINGLERELATEDSTANCECONTENT_FULLVIEW:
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
            self::MODULE_SCROLLINNER_STANCES_NAVIGATOR => [UserStance_Module_Processor_CustomPreviewPostLayouts::class, UserStance_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_STANCE_NAVIGATOR],
            self::MODULE_SCROLLINNER_STANCES_ADDONS => [UserStance_Module_Processor_CustomPreviewPostLayouts::class, UserStance_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_STANCE_CONTENTAUTHORREFERENCED],
            self::MODULE_SCROLLINNER_STANCES_FULLVIEW => [UserStance_Module_Processor_CustomFullViewLayouts::class, UserStance_Module_Processor_CustomFullViewLayouts::MODULE_LAYOUT_FULLVIEW_STANCE],
            self::MODULE_SCROLLINNER_STANCES_THUMBNAIL => [UserStance_Module_Processor_CustomPreviewPostLayouts::class, UserStance_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_STANCE_THUMBNAIL],
            self::MODULE_SCROLLINNER_STANCES_LIST => [UserStance_Module_Processor_CustomPreviewPostLayouts::class, UserStance_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_STANCE_CONTENTAUTHORREFERENCED],
            self::MODULE_SCROLLINNER_AUTHORSTANCES_FULLVIEW => [UserStance_Module_Processor_CustomFullViewLayouts::class, UserStance_Module_Processor_CustomFullViewLayouts::MODULE_LAYOUT_FULLVIEW_STANCE],
            self::MODULE_SCROLLINNER_AUTHORSTANCES_THUMBNAIL => [UserStance_Module_Processor_CustomPreviewPostLayouts::class, UserStance_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_STANCE_THUMBNAIL],
            self::MODULE_SCROLLINNER_AUTHORSTANCES_LIST => [UserStance_Module_Processor_CustomPreviewPostLayouts::class, UserStance_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_STANCE_CONTENTAUTHORREFERENCED],
            self::MODULE_SCROLLINNER_MYSTANCES_FULLVIEWPREVIEW => [UserStance_Module_Processor_CustomFullViewLayouts::class, UserStance_Module_Processor_CustomFullViewLayouts::MODULE_LAYOUT_FULLVIEW_STANCE],
            self::MODULE_SCROLLINNER_SINGLERELATEDSTANCECONTENT_FULLVIEW => [UserStance_Module_Processor_CustomFullViewLayouts::class, UserStance_Module_Processor_CustomFullViewLayouts::MODULE_LAYOUT_FULLVIEW_STANCE],
        );

        if ($layout = $layouts[$module[1]] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}


