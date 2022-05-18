<?php
use PoP\Application\Constants\Actions;
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\State\ApplicationState;

class PoP_Module_Processor_CustomFullViewLayouts extends PoP_Module_Processor_CustomFullViewLayoutsBase
{
    public final const COMPONENT_LAYOUT_FULLVIEW = 'layout-fullview';
    public final const COMPONENT_LAYOUT_FULLVIEW_HIGHLIGHT = 'layout-fullview-highlight';
    public final const COMPONENT_LAYOUT_FULLVIEW_POST = 'layout-fullview-post';
    public final const COMPONENT_AUTHORLAYOUT_FULLVIEW = 'authorlayout-fullview';
    public final const COMPONENT_AUTHORLAYOUT_FULLVIEW_HIGHLIGHT = 'authorlayout-fullview-highlight';
    public final const COMPONENT_AUTHORLAYOUT_FULLVIEW_POST = 'authorlayout-fullview-post';
    public final const COMPONENT_SINGLELAYOUT_FULLVIEW = 'singlelayout-fullview';
    public final const COMPONENT_SINGLELAYOUT_FULLVIEW_HIGHLIGHT = 'singlelayout-fullview-highlight';
    public final const COMPONENT_SINGLELAYOUT_FULLVIEW_POST = 'singlelayout-fullview-post';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_FULLVIEW],
            [self::class, self::COMPONENT_LAYOUT_FULLVIEW_HIGHLIGHT],
            [self::class, self::COMPONENT_LAYOUT_FULLVIEW_POST],
            [self::class, self::COMPONENT_AUTHORLAYOUT_FULLVIEW],
            [self::class, self::COMPONENT_AUTHORLAYOUT_FULLVIEW_HIGHLIGHT],
            [self::class, self::COMPONENT_AUTHORLAYOUT_FULLVIEW_POST],
            [self::class, self::COMPONENT_SINGLELAYOUT_FULLVIEW],
            [self::class, self::COMPONENT_SINGLELAYOUT_FULLVIEW_HIGHLIGHT],
            [self::class, self::COMPONENT_SINGLELAYOUT_FULLVIEW_POST],
        );
    }

    public function getFooterSubmodules(array $component)
    {
        $ret = parent::getFooterSubmodules($component);

        $loadingLazy = in_array(Actions::LOADLAZY, \PoP\Root\App::getState('actions'));
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_FULLVIEW_POST:
            case self::COMPONENT_AUTHORLAYOUT_FULLVIEW_POST:
            case self::COMPONENT_SINGLELAYOUT_FULLVIEW_POST:
                $ret[] = [PoP_Module_Processor_ViewComponentButtonWrappers::class, PoP_Module_Processor_ViewComponentButtonWrappers::COMPONENT_LAYOUTWRAPPER_POSTCONCLUSIONSIDEBAR_HORIZONTAL];
                $ret[] = [PoP_Module_Processor_CustomWrapperLayouts::class, PoP_Module_Processor_CustomWrapperLayouts::COMPONENT_LAYOUTWRAPPER_USERPOSTINTERACTION];
                if ($loadingLazy) {
                    $ret[] = [PoP_Module_Processor_HighlightReferencedbyLayouts::class, PoP_Module_Processor_HighlightReferencedbyLayouts::COMPONENT_SUBCOMPONENT_HIGHLIGHTS];
                    $ret[] = [PoP_Module_Processor_ReferencedbyLayouts::class, PoP_Module_Processor_ReferencedbyLayouts::COMPONENT_SUBCOMPONENT_REFERENCEDBY_SIMPLEVIEW];
                    $ret[] = [PoP_Module_Processor_PostCommentSubcomponentLayouts::class, PoP_Module_Processor_PostCommentSubcomponentLayouts::COMPONENT_SUBCOMPONENT_POSTCOMMENTS];
                } else {
                    $ret[] = [PoP_Module_Processor_CustomWrapperLayouts::class, PoP_Module_Processor_CustomWrapperLayouts::COMPONENT_CODEWRAPPER_LAZYLOADINGSPINNER];
                    $ret[] = [PoP_Module_Processor_HighlightReferencedbyLayouts::class, PoP_Module_Processor_HighlightReferencedbyLayouts::COMPONENT_LAZYSUBCOMPONENT_HIGHLIGHTS];
                    $ret[] = [PoP_Module_Processor_ReferencedbyLayouts::class, PoP_Module_Processor_ReferencedbyLayouts::COMPONENT_LAZYSUBCOMPONENT_REFERENCEDBY];
                    $ret[] = [PoP_Module_Processor_PostCommentSubcomponentLayouts::class, PoP_Module_Processor_PostCommentSubcomponentLayouts::COMPONENT_LAZYSUBCOMPONENT_POSTCOMMENTS];
                }
                break;

            case self::COMPONENT_LAYOUT_FULLVIEW_HIGHLIGHT:
            case self::COMPONENT_AUTHORLAYOUT_FULLVIEW_HIGHLIGHT:
            case self::COMPONENT_SINGLELAYOUT_FULLVIEW_HIGHLIGHT:
                $ret[] = [PoP_Module_Processor_ViewComponentButtonWrappers::class, PoP_Module_Processor_ViewComponentButtonWrappers::COMPONENT_LAYOUTWRAPPER_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL];
                $ret[] = [PoP_Module_Processor_CustomWrapperLayouts::class, PoP_Module_Processor_CustomWrapperLayouts::COMPONENT_LAYOUTWRAPPER_USERHIGHLIGHTPOSTINTERACTION];
                if ($loadingLazy) {
                    $ret[] = [PoP_Module_Processor_PostCommentSubcomponentLayouts::class, PoP_Module_Processor_PostCommentSubcomponentLayouts::COMPONENT_SUBCOMPONENT_POSTCOMMENTS];
                } else {
                    $ret[] = [PoP_Module_Processor_CustomWrapperLayouts::class, PoP_Module_Processor_CustomWrapperLayouts::COMPONENT_CODEWRAPPER_LAZYLOADINGSPINNER];
                    $ret[] = [PoP_Module_Processor_PostCommentSubcomponentLayouts::class, PoP_Module_Processor_PostCommentSubcomponentLayouts::COMPONENT_LAZYSUBCOMPONENT_NOHEADERPOSTCOMMENTS];
                }
                break;
        }

        return $ret;
    }

    public function getSidebarSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_FULLVIEW:
            case self::COMPONENT_LAYOUT_FULLVIEW_HIGHLIGHT:
            case self::COMPONENT_LAYOUT_FULLVIEW_POST:
            case self::COMPONENT_AUTHORLAYOUT_FULLVIEW:
            case self::COMPONENT_AUTHORLAYOUT_FULLVIEW_HIGHLIGHT:
            case self::COMPONENT_AUTHORLAYOUT_FULLVIEW_POST:
            case self::COMPONENT_SINGLELAYOUT_FULLVIEW:
            case self::COMPONENT_SINGLELAYOUT_FULLVIEW_HIGHLIGHT:
            case self::COMPONENT_SINGLELAYOUT_FULLVIEW_POST:
                $sidebars = array(
                    self::COMPONENT_LAYOUT_FULLVIEW => [PoP_Module_Processor_CustomPostLayoutSidebars::class, PoP_Module_Processor_CustomPostLayoutSidebars::COMPONENT_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL],
                    self::COMPONENT_LAYOUT_FULLVIEW_HIGHLIGHT => [PoP_Module_Processor_CustomPostLayoutSidebars::class, PoP_Module_Processor_CustomPostLayoutSidebars::COMPONENT_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_HIGHLIGHT],
                    self::COMPONENT_LAYOUT_FULLVIEW_POST => [PoP_Module_Processor_CustomPostLayoutSidebars::class, PoP_Module_Processor_CustomPostLayoutSidebars::COMPONENT_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_POST],
                    self::COMPONENT_AUTHORLAYOUT_FULLVIEW => [PoP_Module_Processor_CustomPostLayoutSidebars::class, PoP_Module_Processor_CustomPostLayoutSidebars::COMPONENT_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL],
                    self::COMPONENT_AUTHORLAYOUT_FULLVIEW_HIGHLIGHT => [PoP_Module_Processor_CustomPostLayoutSidebars::class, PoP_Module_Processor_CustomPostLayoutSidebars::COMPONENT_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_HIGHLIGHT],
                    self::COMPONENT_AUTHORLAYOUT_FULLVIEW_POST => [PoP_Module_Processor_CustomPostLayoutSidebars::class, PoP_Module_Processor_CustomPostLayoutSidebars::COMPONENT_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_POST],
                    self::COMPONENT_SINGLELAYOUT_FULLVIEW => [PoP_Module_Processor_CustomPostLayoutSidebars::class, PoP_Module_Processor_CustomPostLayoutSidebars::COMPONENT_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL],
                    self::COMPONENT_SINGLELAYOUT_FULLVIEW_HIGHLIGHT => [PoP_Module_Processor_CustomPostLayoutSidebars::class, PoP_Module_Processor_CustomPostLayoutSidebars::COMPONENT_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_HIGHLIGHT],
                    self::COMPONENT_SINGLELAYOUT_FULLVIEW_POST => [PoP_Module_Processor_CustomPostLayoutSidebars::class, PoP_Module_Processor_CustomPostLayoutSidebars::COMPONENT_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_POST],
                );

                return $sidebars[$component[1]];
        }

        return parent::getSidebarSubmodule($component);
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $ret = parent::getImmutableConfiguration($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_FULLVIEW_HIGHLIGHT:
            case self::COMPONENT_AUTHORLAYOUT_FULLVIEW_HIGHLIGHT:
            case self::COMPONENT_SINGLELAYOUT_FULLVIEW_HIGHLIGHT:
                $ret[GD_JS_CLASSES]['content'] = 'well readable';
                break;
        }
        
        return $ret;
    }
}



