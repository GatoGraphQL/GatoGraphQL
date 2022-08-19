<?php

class PoPTheme_Wassup_AE_Module_Processor_FullViewLayouts extends PoP_Module_Processor_CustomFullViewLayoutsBase
{
    public final const COMPONENT_LAYOUT_AUTOMATEDEMAILS_FULLVIEW_POST = 'layout-automatedemails-fullview-post';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_FULLVIEW_POST,
        );
    }

    // function getFooterSubcomponents(\PoP\ComponentModel\Component\Component $component) {

    //     $ret = parent::getFooterSubcomponents($component);

    //     switch ($component->name) {

    //         case self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_FULLVIEW_POST:

    //             $ret[] = [PoP_Module_Processor_QuicklinkButtonGroups::class, PoP_Module_Processor_QuicklinkButtonGroups::COMPONENT_QUICKLINKBUTTONGROUP_COMMENTS_LABEL];
    //             break;
    //     }

    //     return $ret;
    // }

    public function getSidebarSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_FULLVIEW_POST:
                $sidebars = array(
                    self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_FULLVIEW_POST => [PoPTheme_Wassup_AE_Module_Processor_CustomPostLayoutSidebars::class, PoPTheme_Wassup_AE_Module_Processor_CustomPostLayoutSidebars::COMPONENT_LAYOUT_AUTOMATEDEMAILS_POSTSIDEBARCOMPACT_HORIZONTAL_POST],
                );

                return $sidebars[$component->name];
        }

        return parent::getSidebarSubcomponent($component);
    }
}



