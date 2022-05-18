<?php

class PoP_ContentPostLinks_Module_Processor_CustomFullViewLayouts extends PoP_Module_Processor_CustomFullViewLayoutsBase
{
    public final const MODULE_LAYOUT_FULLVIEW_LINK = 'layout-fullview-link';
    public final const MODULE_AUTHORLAYOUT_FULLVIEW_LINK = 'authorlayout-fullview-link';
    public final const MODULE_SINGLELAYOUT_FULLVIEW_LINK = 'singlelayout-fullview-link';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_FULLVIEW_LINK],
            [self::class, self::MODULE_AUTHORLAYOUT_FULLVIEW_LINK],
            [self::class, self::MODULE_SINGLELAYOUT_FULLVIEW_LINK],
        );
    }

    public function getFooterSubmodules(array $componentVariation)
    {
        $ret = parent::getFooterSubmodules($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_FULLVIEW_LINK:
            case self::MODULE_AUTHORLAYOUT_FULLVIEW_LINK:
            case self::MODULE_SINGLELAYOUT_FULLVIEW_LINK:
                $ret[] = [PoP_Module_Processor_ViewComponentButtonWrappers::class, PoP_Module_Processor_ViewComponentButtonWrappers::MODULE_LAYOUTWRAPPER_POSTCONCLUSIONSIDEBAR_HORIZONTAL];
                $ret[] = [PoP_Module_Processor_CustomWrapperLayouts::class, PoP_Module_Processor_CustomWrapperLayouts::MODULE_LAYOUTWRAPPER_USERPOSTINTERACTION];
                $ret[] = [PoP_Module_Processor_CustomWrapperLayouts::class, PoP_Module_Processor_CustomWrapperLayouts::MODULE_CODEWRAPPER_LAZYLOADINGSPINNER];
                $ret[] = [PoP_Module_Processor_HighlightReferencedbyLayouts::class, PoP_Module_Processor_HighlightReferencedbyLayouts::MODULE_LAZYSUBCOMPONENT_HIGHLIGHTS];
                $ret[] = [PoP_Module_Processor_ReferencedbyLayouts::class, PoP_Module_Processor_ReferencedbyLayouts::MODULE_LAZYSUBCOMPONENT_REFERENCEDBY];
                $ret[] = [PoP_Module_Processor_PostCommentSubcomponentLayouts::class, PoP_Module_Processor_PostCommentSubcomponentLayouts::MODULE_LAZYSUBCOMPONENT_POSTCOMMENTS];
                break;
        }

        return $ret;
    }

    public function getSidebarSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_FULLVIEW_LINK:
            case self::MODULE_AUTHORLAYOUT_FULLVIEW_LINK:
            case self::MODULE_SINGLELAYOUT_FULLVIEW_LINK:
                $sidebars = array(
                    self::MODULE_LAYOUT_FULLVIEW_LINK => [PoP_ContentPostLinks_Module_Processor_CustomPostLayoutSidebars::class, PoP_ContentPostLinks_Module_Processor_CustomPostLayoutSidebars::MODULE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_LINK],
                    self::MODULE_AUTHORLAYOUT_FULLVIEW_LINK => [PoP_ContentPostLinks_Module_Processor_CustomPostLayoutSidebars::class, PoP_ContentPostLinks_Module_Processor_CustomPostLayoutSidebars::MODULE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_LINK],
                    self::MODULE_SINGLELAYOUT_FULLVIEW_LINK => [PoP_ContentPostLinks_Module_Processor_CustomPostLayoutSidebars::class, PoP_ContentPostLinks_Module_Processor_CustomPostLayoutSidebars::MODULE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_LINK],
                );

                return $sidebars[$componentVariation[1]];
        }

        return parent::getSidebarSubmodule($componentVariation);
    }
}



