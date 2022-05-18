<?php

class PoP_UserCommunities_Module_Processor_CustomScrollInners extends PoP_Module_Processor_ScrollInnersBase
{
    public final const COMPONENT_SCROLLINNER_MYMEMBERS_FULLVIEWPREVIEW = 'scrollinner-mymembers-fullviewpreview';
    public final const COMPONENT_SCROLLINNER_COMMUNITIES_DETAILS = 'scrollinner-communities-details';
    public final const COMPONENT_SCROLLINNER_COMMUNITIES_FULLVIEW = 'scrollinner-communities-fullview';
    public final const COMPONENT_SCROLLINNER_COMMUNITIES_THUMBNAIL = 'scrollinner-communities-thumbnail';
    public final const COMPONENT_SCROLLINNER_COMMUNITIES_LIST = 'scrollinner-communities-list';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_SCROLLINNER_MYMEMBERS_FULLVIEWPREVIEW],
            [self::class, self::COMPONENT_SCROLLINNER_COMMUNITIES_DETAILS],
            [self::class, self::COMPONENT_SCROLLINNER_COMMUNITIES_FULLVIEW],
            [self::class, self::COMPONENT_SCROLLINNER_COMMUNITIES_THUMBNAIL],
            [self::class, self::COMPONENT_SCROLLINNER_COMMUNITIES_LIST],
        );
    }

    public function getLayoutGrid(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_SCROLLINNER_COMMUNITIES_THUMBNAIL:
                return array(
                    'row-items' => 3,
                    'class' => 'col-xsm-4'
                );

            case self::COMPONENT_SCROLLINNER_MYMEMBERS_FULLVIEWPREVIEW:
            case self::COMPONENT_SCROLLINNER_COMMUNITIES_DETAILS:
            case self::COMPONENT_SCROLLINNER_COMMUNITIES_FULLVIEW:
            case self::COMPONENT_SCROLLINNER_COMMUNITIES_LIST:
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

        $layouts = array(
            self::COMPONENT_SCROLLINNER_COMMUNITIES_DETAILS => [GD_UserCommunities_Module_Processor_CustomPreviewUserLayouts::class, GD_UserCommunities_Module_Processor_CustomPreviewUserLayouts::COMPONENT_LAYOUT_PREVIEWUSER_COMMUNITY_DETAILS],
            self::COMPONENT_SCROLLINNER_COMMUNITIES_THUMBNAIL => [GD_UserCommunities_Module_Processor_CustomPreviewUserLayouts::class, GD_UserCommunities_Module_Processor_CustomPreviewUserLayouts::COMPONENT_LAYOUT_PREVIEWUSER_COMMUNITY_THUMBNAIL],
            self::COMPONENT_SCROLLINNER_COMMUNITIES_LIST => [GD_UserCommunities_Module_Processor_CustomPreviewUserLayouts::class, GD_UserCommunities_Module_Processor_CustomPreviewUserLayouts::COMPONENT_LAYOUT_PREVIEWUSER_COMMUNITY_LIST],
            self::COMPONENT_SCROLLINNER_COMMUNITIES_FULLVIEW => [GD_UserCommunities_Module_Processor_CustomFullUserLayouts::class, GD_UserCommunities_Module_Processor_CustomFullUserLayouts::COMPONENT_LAYOUT_FULLUSER_COMMUNITY],
            self::COMPONENT_SCROLLINNER_MYMEMBERS_FULLVIEWPREVIEW => [PoP_Module_Processor_MultipleUserLayouts::class, PoP_Module_Processor_MultipleUserLayouts::COMPONENT_LAYOUT_MULTIPLEUSER_FULLUSER],
        );

        if ($layout = $layouts[$component[1]] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}


