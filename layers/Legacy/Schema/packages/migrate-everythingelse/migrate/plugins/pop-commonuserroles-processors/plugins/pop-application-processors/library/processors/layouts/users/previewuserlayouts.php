<?php

class GD_URE_Module_Processor_CustomPreviewUserLayouts extends PoP_Module_Processor_CustomPreviewUserLayoutsBase
{
    public final const MODULE_LAYOUT_PREVIEWUSER_ORGANIZATION_DETAILS = 'layout-previewuser-organization-details';
    public final const MODULE_LAYOUT_PREVIEWUSER_INDIVIDUAL_DETAILS = 'layout-previewuser-individual-details';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_PREVIEWUSER_ORGANIZATION_DETAILS],
            [self::class, self::MODULE_LAYOUT_PREVIEWUSER_INDIVIDUAL_DETAILS],
        );
    }

    public function getQuicklinkgroupTopSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWUSER_ORGANIZATION_DETAILS:
            case self::MODULE_LAYOUT_PREVIEWUSER_INDIVIDUAL_DETAILS:
                return [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::MODULE_QUICKLINKGROUP_USER];
        }

        return parent::getQuicklinkgroupTopSubmodule($module);
    }

    public function getTitleHtmlmarkup(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWUSER_ORGANIZATION_DETAILS:
            case self::MODULE_LAYOUT_PREVIEWUSER_INDIVIDUAL_DETAILS:
                return 'h3';
        }

        return parent::getTitleHtmlmarkup($module, $props);
    }

    public function getQuicklinkgroupBottomSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWUSER_ORGANIZATION_DETAILS:
            case self::MODULE_LAYOUT_PREVIEWUSER_INDIVIDUAL_DETAILS:
                return [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::MODULE_QUICKLINKGROUP_USERBOTTOM];
        }

        return parent::getQuicklinkgroupBottomSubmodule($module);
    }

    public function getBelowexcerptLayoutSubmodules(array $module)
    {
        $ret = parent::getBelowexcerptLayoutSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWUSER_ORGANIZATION_DETAILS:
                $ret[] = [GD_URE_Module_Processor_LayoutMultipleComponents::class, GD_URE_Module_Processor_LayoutMultipleComponents::MODULE_MULTICOMPONENT_ORGANIZATIONDETAILS];
                if (defined('POP_USERCOMMUNITIESPROCESSORS_INITIALIZED')) {
                    $ret[] = [GD_URE_Module_Processor_MembersLayoutWrappers::class, GD_URE_Module_Processor_MembersLayoutWrappers::MODULE_URE_LAYOUTWRAPPER_COMMUNITYMEMBERS];
                }
                break;

            case self::MODULE_LAYOUT_PREVIEWUSER_INDIVIDUAL_DETAILS:
                $ret[] = [GD_URE_Module_Processor_CategoriesLayouts::class, GD_URE_Module_Processor_CategoriesLayouts::MODULE_LAYOUT_INDIVIDUALINTERESTS];
                break;
        }

        return $ret;
    }

    public function getBelowavatarLayoutSubmodules(array $module)
    {
        $ret = parent::getBelowavatarLayoutSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWUSER_ORGANIZATION_DETAILS:
            case self::MODULE_LAYOUT_PREVIEWUSER_INDIVIDUAL_DETAILS:
                $ret[] = [PoP_Module_Processor_LocationViewComponentButtonWrapperss::class, PoP_Module_Processor_LocationViewComponentButtonWrapperss::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_USERLOCATIONS];
                break;
        }

        return $ret;
    }

    public function getUseravatarSubmodule(array $module)
    {
        if (defined('POP_AVATARPROCESSORS_INITIALIZED')) {
            switch ($module[1]) {
                case self::MODULE_LAYOUT_PREVIEWUSER_ORGANIZATION_DETAILS:
                case self::MODULE_LAYOUT_PREVIEWUSER_INDIVIDUAL_DETAILS:
                    return [PoP_Module_Processor_CustomUserAvatarLayouts::class, PoP_Module_Processor_CustomUserAvatarLayouts::MODULE_LAYOUT_USERAVATAR_150_RESPONSIVE];
            }
        }

        return parent::getUseravatarSubmodule($module);
    }


    public function showExcerpt(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWUSER_ORGANIZATION_DETAILS:
            case self::MODULE_LAYOUT_PREVIEWUSER_INDIVIDUAL_DETAILS:
                return true;
        }

        return parent::showExcerpt($module);
    }

    public function horizontalLayout(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWUSER_ORGANIZATION_DETAILS:
            case self::MODULE_LAYOUT_PREVIEWUSER_INDIVIDUAL_DETAILS:
                return true;
        }

        return parent::horizontalLayout($module);
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        switch ($module[1]) {
            case self::MODULE_LAYOUT_PREVIEWUSER_ORGANIZATION_DETAILS:
            case self::MODULE_LAYOUT_PREVIEWUSER_INDIVIDUAL_DETAILS:
                $ret[GD_JS_CLASSES]['belowavatar'] = 'bg-info text-info belowavatar';
                break;
        }

        return $ret;
    }
}


