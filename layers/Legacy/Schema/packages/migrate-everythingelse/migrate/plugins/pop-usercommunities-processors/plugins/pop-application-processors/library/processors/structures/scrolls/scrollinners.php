<?php

class PoP_UserCommunities_Module_Processor_CustomScrollInners extends PoP_Module_Processor_ScrollInnersBase
{
    public final const MODULE_SCROLLINNER_MYMEMBERS_FULLVIEWPREVIEW = 'scrollinner-mymembers-fullviewpreview';
    public final const MODULE_SCROLLINNER_COMMUNITIES_DETAILS = 'scrollinner-communities-details';
    public final const MODULE_SCROLLINNER_COMMUNITIES_FULLVIEW = 'scrollinner-communities-fullview';
    public final const MODULE_SCROLLINNER_COMMUNITIES_THUMBNAIL = 'scrollinner-communities-thumbnail';
    public final const MODULE_SCROLLINNER_COMMUNITIES_LIST = 'scrollinner-communities-list';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SCROLLINNER_MYMEMBERS_FULLVIEWPREVIEW],
            [self::class, self::MODULE_SCROLLINNER_COMMUNITIES_DETAILS],
            [self::class, self::MODULE_SCROLLINNER_COMMUNITIES_FULLVIEW],
            [self::class, self::MODULE_SCROLLINNER_COMMUNITIES_THUMBNAIL],
            [self::class, self::MODULE_SCROLLINNER_COMMUNITIES_LIST],
        );
    }

    public function getLayoutGrid(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_SCROLLINNER_COMMUNITIES_THUMBNAIL:
                return array(
                    'row-items' => 3,
                    'class' => 'col-xsm-4'
                );

            case self::MODULE_SCROLLINNER_MYMEMBERS_FULLVIEWPREVIEW:
            case self::MODULE_SCROLLINNER_COMMUNITIES_DETAILS:
            case self::MODULE_SCROLLINNER_COMMUNITIES_FULLVIEW:
            case self::MODULE_SCROLLINNER_COMMUNITIES_LIST:
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
            self::MODULE_SCROLLINNER_COMMUNITIES_DETAILS => [GD_UserCommunities_Module_Processor_CustomPreviewUserLayouts::class, GD_UserCommunities_Module_Processor_CustomPreviewUserLayouts::MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_DETAILS],
            self::MODULE_SCROLLINNER_COMMUNITIES_THUMBNAIL => [GD_UserCommunities_Module_Processor_CustomPreviewUserLayouts::class, GD_UserCommunities_Module_Processor_CustomPreviewUserLayouts::MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_THUMBNAIL],
            self::MODULE_SCROLLINNER_COMMUNITIES_LIST => [GD_UserCommunities_Module_Processor_CustomPreviewUserLayouts::class, GD_UserCommunities_Module_Processor_CustomPreviewUserLayouts::MODULE_LAYOUT_PREVIEWUSER_COMMUNITY_LIST],
            self::MODULE_SCROLLINNER_COMMUNITIES_FULLVIEW => [GD_UserCommunities_Module_Processor_CustomFullUserLayouts::class, GD_UserCommunities_Module_Processor_CustomFullUserLayouts::MODULE_LAYOUT_FULLUSER_COMMUNITY],
            self::MODULE_SCROLLINNER_MYMEMBERS_FULLVIEWPREVIEW => [PoP_Module_Processor_MultipleUserLayouts::class, PoP_Module_Processor_MultipleUserLayouts::MODULE_LAYOUT_MULTIPLEUSER_FULLUSER],
        );

        if ($layout = $layouts[$module[1]] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}


