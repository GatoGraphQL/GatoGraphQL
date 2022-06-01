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

    public function getQuicklinkgroupTopSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWUSER_ORGANIZATION_DETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWUSER_INDIVIDUAL_DETAILS:
                return [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::COMPONENT_QUICKLINKGROUP_USER];
        }

        return parent::getQuicklinkgroupTopSubcomponent($component);
    }

    public function getTitleHtmlmarkup(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWUSER_ORGANIZATION_DETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWUSER_INDIVIDUAL_DETAILS:
                return 'h3';
        }

        return parent::getTitleHtmlmarkup($component, $props);
    }

    public function getQuicklinkgroupBottomSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWUSER_ORGANIZATION_DETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWUSER_INDIVIDUAL_DETAILS:
                return [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::COMPONENT_QUICKLINKGROUP_USERBOTTOM];
        }

        return parent::getQuicklinkgroupBottomSubcomponent($component);
    }

    public function getBelowexcerptLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getBelowexcerptLayoutSubcomponents($component);

        switch ($component->name) {
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

    public function getBelowavatarLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getBelowavatarLayoutSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWUSER_ORGANIZATION_DETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWUSER_INDIVIDUAL_DETAILS:
                $ret[] = [PoP_Module_Processor_LocationViewComponentButtonWrapperss::class, PoP_Module_Processor_LocationViewComponentButtonWrapperss::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_USERLOCATIONS];
                break;
        }

        return $ret;
    }

    public function getUseravatarSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        if (defined('POP_AVATARPROCESSORS_INITIALIZED')) {
            switch ($component->name) {
                case self::COMPONENT_LAYOUT_PREVIEWUSER_ORGANIZATION_DETAILS:
                case self::COMPONENT_LAYOUT_PREVIEWUSER_INDIVIDUAL_DETAILS:
                    return [PoP_Module_Processor_CustomUserAvatarLayouts::class, PoP_Module_Processor_CustomUserAvatarLayouts::COMPONENT_LAYOUT_USERAVATAR_150_RESPONSIVE];
            }
        }

        return parent::getUseravatarSubcomponent($component);
    }


    public function showExcerpt(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWUSER_ORGANIZATION_DETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWUSER_INDIVIDUAL_DETAILS:
                return true;
        }

        return parent::showExcerpt($component);
    }

    public function horizontalLayout(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWUSER_ORGANIZATION_DETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWUSER_INDIVIDUAL_DETAILS:
                return true;
        }

        return parent::horizontalLayout($component);
    }

    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        switch ($component->name) {
            case self::COMPONENT_LAYOUT_PREVIEWUSER_ORGANIZATION_DETAILS:
            case self::COMPONENT_LAYOUT_PREVIEWUSER_INDIVIDUAL_DETAILS:
                $ret[GD_JS_CLASSES]['belowavatar'] = 'bg-info text-info belowavatar';
                break;
        }

        return $ret;
    }
}


