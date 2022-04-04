<?php

class PoP_UserCommunities_EM_Module_Processor_CustomScrollInners extends PoP_Module_Processor_ScrollInnersBase
{
    public final const MODULE_SCROLLINNER_COMMUNITIES_MAP = 'scrollinner-communities-map';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SCROLLINNER_COMMUNITIES_MAP],
        );
    }

    public function getLayoutGrid(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_SCROLLINNER_COMMUNITIES_MAP:
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
            self::MODULE_SCROLLINNER_COMMUNITIES_MAP => [GD_UserCommunities_Module_Processor_CustomPreviewUserLayouts::class, GD_UserCommunities_Module_Processor_CustomPreviewUserLayouts::MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_MAPDETAILS],
        );

        if ($layout = $layouts[$module[1]] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}


