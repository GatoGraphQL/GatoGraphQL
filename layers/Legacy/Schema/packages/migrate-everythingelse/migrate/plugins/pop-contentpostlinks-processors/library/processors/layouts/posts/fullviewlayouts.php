<?php

class PoP_ContentPostLinks_Module_Processor_CustomFullViewLayouts extends PoP_Module_Processor_CustomFullViewLayoutsBase
{
    public final const COMPONENT_LAYOUT_FULLVIEW_LINK = 'layout-fullview-link';
    public final const COMPONENT_AUTHORLAYOUT_FULLVIEW_LINK = 'authorlayout-fullview-link';
    public final const COMPONENT_SINGLELAYOUT_FULLVIEW_LINK = 'singlelayout-fullview-link';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_FULLVIEW_LINK,
            self::COMPONENT_AUTHORLAYOUT_FULLVIEW_LINK,
            self::COMPONENT_SINGLELAYOUT_FULLVIEW_LINK,
        );
    }

    public function getFooterSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getFooterSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_LAYOUT_FULLVIEW_LINK:
            case self::COMPONENT_AUTHORLAYOUT_FULLVIEW_LINK:
            case self::COMPONENT_SINGLELAYOUT_FULLVIEW_LINK:
                $ret[] = [PoP_Module_Processor_ViewComponentButtonWrappers::class, PoP_Module_Processor_ViewComponentButtonWrappers::COMPONENT_LAYOUTWRAPPER_POSTCONCLUSIONSIDEBAR_HORIZONTAL];
                $ret[] = [PoP_Module_Processor_CustomWrapperLayouts::class, PoP_Module_Processor_CustomWrapperLayouts::COMPONENT_LAYOUTWRAPPER_USERPOSTINTERACTION];
                $ret[] = [PoP_Module_Processor_CustomWrapperLayouts::class, PoP_Module_Processor_CustomWrapperLayouts::COMPONENT_CODEWRAPPER_LAZYLOADINGSPINNER];
                $ret[] = [PoP_Module_Processor_HighlightReferencedbyLayouts::class, PoP_Module_Processor_HighlightReferencedbyLayouts::COMPONENT_LAZYSUBCOMPONENT_HIGHLIGHTS];
                $ret[] = [PoP_Module_Processor_ReferencedbyLayouts::class, PoP_Module_Processor_ReferencedbyLayouts::COMPONENT_LAZYSUBCOMPONENT_REFERENCEDBY];
                $ret[] = [PoP_Module_Processor_PostCommentSubcomponentLayouts::class, PoP_Module_Processor_PostCommentSubcomponentLayouts::COMPONENT_LAZYSUBCOMPONENT_POSTCOMMENTS];
                break;
        }

        return $ret;
    }

    public function getSidebarSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_FULLVIEW_LINK:
            case self::COMPONENT_AUTHORLAYOUT_FULLVIEW_LINK:
            case self::COMPONENT_SINGLELAYOUT_FULLVIEW_LINK:
                $sidebars = array(
                    self::COMPONENT_LAYOUT_FULLVIEW_LINK => [PoP_ContentPostLinks_Module_Processor_CustomPostLayoutSidebars::class, PoP_ContentPostLinks_Module_Processor_CustomPostLayoutSidebars::COMPONENT_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_LINK],
                    self::COMPONENT_AUTHORLAYOUT_FULLVIEW_LINK => [PoP_ContentPostLinks_Module_Processor_CustomPostLayoutSidebars::class, PoP_ContentPostLinks_Module_Processor_CustomPostLayoutSidebars::COMPONENT_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_LINK],
                    self::COMPONENT_SINGLELAYOUT_FULLVIEW_LINK => [PoP_ContentPostLinks_Module_Processor_CustomPostLayoutSidebars::class, PoP_ContentPostLinks_Module_Processor_CustomPostLayoutSidebars::COMPONENT_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_LINK],
                );

                return $sidebars[$component->name];
        }

        return parent::getSidebarSubcomponent($component);
    }
}



