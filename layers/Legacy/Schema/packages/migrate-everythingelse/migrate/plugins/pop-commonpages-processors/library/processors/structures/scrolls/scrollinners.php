<?php

class GD_Custom_Module_Processor_CustomScrollInners extends PoP_Module_Processor_ScrollInnersBase
{
    public final const MODULE_SCROLLINNER_WHOWEARE_DETAILS = 'scrollinner-whoweare-details';
    public final const MODULE_SCROLLINNER_WHOWEARE_THUMBNAIL = 'scrollinner-whoweare-thumbnail';
    public final const MODULE_SCROLLINNER_WHOWEARE_LIST = 'scrollinner-whoweare-list';
    public final const MODULE_SCROLLINNER_WHOWEARE_FULLVIEW = 'scrollinner-whoweare-fullview';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_SCROLLINNER_WHOWEARE_DETAILS],
            [self::class, self::COMPONENT_SCROLLINNER_WHOWEARE_THUMBNAIL],
            [self::class, self::COMPONENT_SCROLLINNER_WHOWEARE_LIST],
            [self::class, self::COMPONENT_SCROLLINNER_WHOWEARE_FULLVIEW],
        );
    }

    public function getLayoutGrid(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_SCROLLINNER_WHOWEARE_THUMBNAIL:
                // Allow ThemeStyle Expansive to override the grid
                return \PoP\Root\App::applyFilters(
                    POP_HOOK_SCROLLINNER_THUMBNAIL_GRID,
                    array(
                        'row-items' => 3,
                        'class' => 'col-xsm-4'
                    )
                );

            case self::COMPONENT_SCROLLINNER_WHOWEARE_DETAILS:
            case self::COMPONENT_SCROLLINNER_WHOWEARE_LIST:
            case self::COMPONENT_SCROLLINNER_WHOWEARE_FULLVIEW:
                return array(
                    'row-items' => 1,
                    'class' => 'col-sm-12'
                );
        }

        return parent::getLayoutGrid($component, $props);
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_SCROLLINNER_WHOWEARE_THUMBNAIL:
                $ret[] = [PoP_Module_Processor_MultipleUserLayouts::class, PoP_Module_Processor_MultipleUserLayouts::COMPONENT_LAYOUT_MULTIPLEUSER_THUMBNAIL];
                break;

            case self::COMPONENT_SCROLLINNER_WHOWEARE_LIST:
                $ret[] = [PoP_Module_Processor_MultipleUserLayouts::class, PoP_Module_Processor_MultipleUserLayouts::COMPONENT_LAYOUT_MULTIPLEUSER_LIST];
                break;
                
            case self::COMPONENT_SCROLLINNER_WHOWEARE_FULLVIEW:
                $ret[] = [PoP_Module_Processor_MultipleUserLayouts::class, PoP_Module_Processor_MultipleUserLayouts::COMPONENT_LAYOUT_MULTIPLEUSER_FULLUSER];
                break;

            case self::COMPONENT_SCROLLINNER_WHOWEARE_DETAILS:
                $ret[] = [GD_ClusterCommonPages_Module_Processor_CustomPreviewUserLayouts::class, GD_ClusterCommonPages_Module_Processor_CustomPreviewUserLayouts::COMPONENT_LAYOUT_PREVIEWUSER_SPONSOR_DETAILS];
                break;
        }

        return $ret;
    }
}


