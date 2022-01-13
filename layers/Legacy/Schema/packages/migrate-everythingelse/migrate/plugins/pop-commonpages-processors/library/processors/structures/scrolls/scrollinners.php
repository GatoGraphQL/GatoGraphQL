<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class GD_Custom_Module_Processor_CustomScrollInners extends PoP_Module_Processor_ScrollInnersBase
{
    public const MODULE_SCROLLINNER_WHOWEARE_DETAILS = 'scrollinner-whoweare-details';
    public const MODULE_SCROLLINNER_WHOWEARE_THUMBNAIL = 'scrollinner-whoweare-thumbnail';
    public const MODULE_SCROLLINNER_WHOWEARE_LIST = 'scrollinner-whoweare-list';
    public const MODULE_SCROLLINNER_WHOWEARE_FULLVIEW = 'scrollinner-whoweare-fullview';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SCROLLINNER_WHOWEARE_DETAILS],
            [self::class, self::MODULE_SCROLLINNER_WHOWEARE_THUMBNAIL],
            [self::class, self::MODULE_SCROLLINNER_WHOWEARE_LIST],
            [self::class, self::MODULE_SCROLLINNER_WHOWEARE_FULLVIEW],
        );
    }

    public function getLayoutGrid(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_SCROLLINNER_WHOWEARE_THUMBNAIL:
                // Allow ThemeStyle Expansive to override the grid
                return \PoP\Root\App::getHookManager()->applyFilters(
                    POP_HOOK_SCROLLINNER_THUMBNAIL_GRID,
                    array(
                        'row-items' => 3,
                        'class' => 'col-xsm-4'
                    )
                );

            case self::MODULE_SCROLLINNER_WHOWEARE_DETAILS:
            case self::MODULE_SCROLLINNER_WHOWEARE_LIST:
            case self::MODULE_SCROLLINNER_WHOWEARE_FULLVIEW:
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

        switch ($module[1]) {
            case self::MODULE_SCROLLINNER_WHOWEARE_THUMBNAIL:
                $ret[] = [PoP_Module_Processor_MultipleUserLayouts::class, PoP_Module_Processor_MultipleUserLayouts::MODULE_LAYOUT_MULTIPLEUSER_THUMBNAIL];
                break;

            case self::MODULE_SCROLLINNER_WHOWEARE_LIST:
                $ret[] = [PoP_Module_Processor_MultipleUserLayouts::class, PoP_Module_Processor_MultipleUserLayouts::MODULE_LAYOUT_MULTIPLEUSER_LIST];
                break;
                
            case self::MODULE_SCROLLINNER_WHOWEARE_FULLVIEW:
                $ret[] = [PoP_Module_Processor_MultipleUserLayouts::class, PoP_Module_Processor_MultipleUserLayouts::MODULE_LAYOUT_MULTIPLEUSER_FULLUSER];
                break;

            case self::MODULE_SCROLLINNER_WHOWEARE_DETAILS:
                $ret[] = [GD_ClusterCommonPages_Module_Processor_CustomPreviewUserLayouts::class, GD_ClusterCommonPages_Module_Processor_CustomPreviewUserLayouts::MODULE_LAYOUT_PREVIEWUSER_SPONSOR_DETAILS];
                break;
        }

        return $ret;
    }
}


