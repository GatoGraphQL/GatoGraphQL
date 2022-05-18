<?php

class PoPTheme_Wassup_AE_Module_Processor_FullViewLayouts extends PoP_Module_Processor_CustomFullViewLayoutsBase
{
    public final const COMPONENT_LAYOUT_AUTOMATEDEMAILS_FULLVIEW_POST = 'layout-automatedemails-fullview-post';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_FULLVIEW_POST],
        );
    }

    // function getFooterSubmodules(array $component) {

    //     $ret = parent::getFooterSubmodules($component);

    //     switch ($component[1]) {

    //         case self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_FULLVIEW_POST:

    //             $ret[] = [PoP_Module_Processor_QuicklinkButtonGroups::class, PoP_Module_Processor_QuicklinkButtonGroups::COMPONENT_QUICKLINKBUTTONGROUP_COMMENTS_LABEL];
    //             break;
    //     }

    //     return $ret;
    // }

    public function getSidebarSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_FULLVIEW_POST:
                $sidebars = array(
                    self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_FULLVIEW_POST => [PoPTheme_Wassup_AE_Module_Processor_CustomPostLayoutSidebars::class, PoPTheme_Wassup_AE_Module_Processor_CustomPostLayoutSidebars::COMPONENT_LAYOUT_AUTOMATEDEMAILS_POSTSIDEBARCOMPACT_HORIZONTAL_POST],
                );

                return $sidebars[$component[1]];
        }

        return parent::getSidebarSubmodule($component);
    }
}



