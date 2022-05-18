<?php

class GD_URE_Module_Processor_CustomPreviewUserLayouts extends PoP_Module_Processor_CustomPreviewUserLayoutsBase
{
    public final const COMPONENT_LAYOUT_PREVIEWUSER_ORGANIZATION_DETAILS = 'layout-previewuser-organization-details';
    public final const COMPONENT_LAYOUT_PREVIEWUSER_INDIVIDUAL_DETAILS = 'layout-previewuser-individual-details';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_PREVIEWUSER_ORGANIZATION_DETAILS],
            [self::class, self::COMPONENT_LAYOUT_PREVIEWUSER_INDIVIDUAL_DETAILS],
        );
    }

    public function getQuicklinkgroupTopSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_PREVIEWUSER_ORGANIZATION_DETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWUSER_INDIVIDUAL_DETAILS:
                return [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::COMPONENT_QUICKLINKGROUP_USER];
        }

        return parent::getQuicklinkgroupTopSubmodule($component);
    }

    public function getTitleHtmlmarkup(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_PREVIEWUSER_ORGANIZATION_DETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWUSER_INDIVIDUAL_DETAILS:
                return 'h3';
        }

        return parent::getTitleHtmlmarkup($component, $props);
    }

    public function getQuicklinkgroupBottomSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_PREVIEWUSER_ORGANIZATION_DETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWUSER_INDIVIDUAL_DETAILS:
                return [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::COMPONENT_QUICKLINKGROUP_USERBOTTOM];
        }

        return parent::getQuicklinkgroupBottomSubmodule($component);
    }

    public function getBelowexcerptLayoutSubmodules(array $component)
    {
        $ret = parent::getBelowexcerptLayoutSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_PREVIEWUSER_ORGANIZATION_DETAILS:
                $ret[] = [GD_URE_Module_Processor_LayoutMultipleComponents::class, GD_URE_Module_Processor_LayoutMultipleComponents::COMPONENT_MULTICOMPONENT_ORGANIZATIONDETAILS];
                if (defined('POP_USERCOMMUNITIESPROCESSORS_INITIALIZED')) {
                    $ret[] = [GD_URE_Module_Processor_MembersLayoutWrappers::class, GD_URE_Module_Processor_MembersLayoutWrappers::COMPONENT_URE_LAYOUTWRAPPER_COMMUNITYMEMBERS];
                }
                break;

            case self::COMPONENT_LAYOUT_PREVIEWUSER_INDIVIDUAL_DETAILS:
                $ret[] = [GD_URE_Module_Processor_CategoriesLayouts::class, GD_URE_Module_Processor_CategoriesLayouts::COMPONENT_LAYOUT_INDIVIDUALINTERESTS];
                break;
        }

        return $ret;
    }

    public function getBelowavatarLayoutSubmodules(array $component)
    {
        $ret = parent::getBelowavatarLayoutSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_PREVIEWUSER_ORGANIZATION_DETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWUSER_INDIVIDUAL_DETAILS:
                $ret[] = [PoP_Module_Processor_LocationViewComponentButtonWrapperss::class, PoP_Module_Processor_LocationViewComponentButtonWrapperss::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_USERLOCATIONS];
                break;
        }

        return $ret;
    }

    public function getUseravatarSubmodule(array $component)
    {
        if (defined('POP_AVATARPROCESSORS_INITIALIZED')) {
            switch ($component[1]) {
                case self::COMPONENT_LAYOUT_PREVIEWUSER_ORGANIZATION_DETAILS:
                case self::COMPONENT_LAYOUT_PREVIEWUSER_INDIVIDUAL_DETAILS:
                    return [PoP_Module_Processor_CustomUserAvatarLayouts::class, PoP_Module_Processor_CustomUserAvatarLayouts::COMPONENT_LAYOUT_USERAVATAR_150_RESPONSIVE];
            }
        }

        return parent::getUseravatarSubmodule($component);
    }


    public function showExcerpt(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_PREVIEWUSER_ORGANIZATION_DETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWUSER_INDIVIDUAL_DETAILS:
                return true;
        }

        return parent::showExcerpt($component);
    }

    public function horizontalLayout(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_PREVIEWUSER_ORGANIZATION_DETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWUSER_INDIVIDUAL_DETAILS:
                return true;
        }

        return parent::horizontalLayout($component);
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_PREVIEWUSER_ORGANIZATION_DETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWUSER_INDIVIDUAL_DETAILS:
                $ret[GD_JS_CLASSES]['belowavatar'] = 'bg-info text-info belowavatar';
                break;
        }

        return $ret;
    }
}


