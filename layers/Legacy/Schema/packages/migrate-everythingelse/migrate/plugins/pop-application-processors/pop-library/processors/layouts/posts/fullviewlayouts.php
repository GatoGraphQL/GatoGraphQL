<?php
use PoP\Application\Constants\Actions;
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoP\ComponentModel\State\ApplicationState;

class PoP_Module_Processor_CustomFullViewLayouts extends PoP_Module_Processor_CustomFullViewLayoutsBase
{
    public const MODULE_LAYOUT_FULLVIEW = 'layout-fullview';
    public const MODULE_LAYOUT_FULLVIEW_HIGHLIGHT = 'layout-fullview-highlight';
    public const MODULE_LAYOUT_FULLVIEW_POST = 'layout-fullview-post';
    public const MODULE_AUTHORLAYOUT_FULLVIEW = 'authorlayout-fullview';
    public const MODULE_AUTHORLAYOUT_FULLVIEW_HIGHLIGHT = 'authorlayout-fullview-highlight';
    public const MODULE_AUTHORLAYOUT_FULLVIEW_POST = 'authorlayout-fullview-post';
    public const MODULE_SINGLELAYOUT_FULLVIEW = 'singlelayout-fullview';
    public const MODULE_SINGLELAYOUT_FULLVIEW_HIGHLIGHT = 'singlelayout-fullview-highlight';
    public const MODULE_SINGLELAYOUT_FULLVIEW_POST = 'singlelayout-fullview-post';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_FULLVIEW],
            [self::class, self::MODULE_LAYOUT_FULLVIEW_HIGHLIGHT],
            [self::class, self::MODULE_LAYOUT_FULLVIEW_POST],
            [self::class, self::MODULE_AUTHORLAYOUT_FULLVIEW],
            [self::class, self::MODULE_AUTHORLAYOUT_FULLVIEW_HIGHLIGHT],
            [self::class, self::MODULE_AUTHORLAYOUT_FULLVIEW_POST],
            [self::class, self::MODULE_SINGLELAYOUT_FULLVIEW],
            [self::class, self::MODULE_SINGLELAYOUT_FULLVIEW_HIGHLIGHT],
            [self::class, self::MODULE_SINGLELAYOUT_FULLVIEW_POST],
        );
    }

    public function getFooterSubmodules(array $module)
    {
        $ret = parent::getFooterSubmodules($module);

        $vars = ApplicationState::getVars();
        $loadingLazy = in_array(Actions::LOADLAZY, \PoP\Root\App::getState('actions'));
        switch ($module[1]) {
            case self::MODULE_LAYOUT_FULLVIEW_POST:
            case self::MODULE_AUTHORLAYOUT_FULLVIEW_POST:
            case self::MODULE_SINGLELAYOUT_FULLVIEW_POST:
                $ret[] = [PoP_Module_Processor_ViewComponentButtonWrappers::class, PoP_Module_Processor_ViewComponentButtonWrappers::MODULE_LAYOUTWRAPPER_POSTCONCLUSIONSIDEBAR_HORIZONTAL];
                $ret[] = [PoP_Module_Processor_CustomWrapperLayouts::class, PoP_Module_Processor_CustomWrapperLayouts::MODULE_LAYOUTWRAPPER_USERPOSTINTERACTION];
                if ($loadingLazy) {
                    $ret[] = [PoP_Module_Processor_HighlightReferencedbyLayouts::class, PoP_Module_Processor_HighlightReferencedbyLayouts::MODULE_SUBCOMPONENT_HIGHLIGHTS];
                    $ret[] = [PoP_Module_Processor_ReferencedbyLayouts::class, PoP_Module_Processor_ReferencedbyLayouts::MODULE_SUBCOMPONENT_REFERENCEDBY_SIMPLEVIEW];
                    $ret[] = [PoP_Module_Processor_PostCommentSubcomponentLayouts::class, PoP_Module_Processor_PostCommentSubcomponentLayouts::MODULE_SUBCOMPONENT_POSTCOMMENTS];
                } else {
                    $ret[] = [PoP_Module_Processor_CustomWrapperLayouts::class, PoP_Module_Processor_CustomWrapperLayouts::MODULE_CODEWRAPPER_LAZYLOADINGSPINNER];
                    $ret[] = [PoP_Module_Processor_HighlightReferencedbyLayouts::class, PoP_Module_Processor_HighlightReferencedbyLayouts::MODULE_LAZYSUBCOMPONENT_HIGHLIGHTS];
                    $ret[] = [PoP_Module_Processor_ReferencedbyLayouts::class, PoP_Module_Processor_ReferencedbyLayouts::MODULE_LAZYSUBCOMPONENT_REFERENCEDBY];
                    $ret[] = [PoP_Module_Processor_PostCommentSubcomponentLayouts::class, PoP_Module_Processor_PostCommentSubcomponentLayouts::MODULE_LAZYSUBCOMPONENT_POSTCOMMENTS];
                }
                break;

            case self::MODULE_LAYOUT_FULLVIEW_HIGHLIGHT:
            case self::MODULE_AUTHORLAYOUT_FULLVIEW_HIGHLIGHT:
            case self::MODULE_SINGLELAYOUT_FULLVIEW_HIGHLIGHT:
                $ret[] = [PoP_Module_Processor_ViewComponentButtonWrappers::class, PoP_Module_Processor_ViewComponentButtonWrappers::MODULE_LAYOUTWRAPPER_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL];
                $ret[] = [PoP_Module_Processor_CustomWrapperLayouts::class, PoP_Module_Processor_CustomWrapperLayouts::MODULE_LAYOUTWRAPPER_USERHIGHLIGHTPOSTINTERACTION];
                if ($loadingLazy) {
                    $ret[] = [PoP_Module_Processor_PostCommentSubcomponentLayouts::class, PoP_Module_Processor_PostCommentSubcomponentLayouts::MODULE_SUBCOMPONENT_POSTCOMMENTS];
                } else {
                    $ret[] = [PoP_Module_Processor_CustomWrapperLayouts::class, PoP_Module_Processor_CustomWrapperLayouts::MODULE_CODEWRAPPER_LAZYLOADINGSPINNER];
                    $ret[] = [PoP_Module_Processor_PostCommentSubcomponentLayouts::class, PoP_Module_Processor_PostCommentSubcomponentLayouts::MODULE_LAZYSUBCOMPONENT_NOHEADERPOSTCOMMENTS];
                }
                break;
        }

        return $ret;
    }

    public function getSidebarSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_FULLVIEW:
            case self::MODULE_LAYOUT_FULLVIEW_HIGHLIGHT:
            case self::MODULE_LAYOUT_FULLVIEW_POST:
            case self::MODULE_AUTHORLAYOUT_FULLVIEW:
            case self::MODULE_AUTHORLAYOUT_FULLVIEW_HIGHLIGHT:
            case self::MODULE_AUTHORLAYOUT_FULLVIEW_POST:
            case self::MODULE_SINGLELAYOUT_FULLVIEW:
            case self::MODULE_SINGLELAYOUT_FULLVIEW_HIGHLIGHT:
            case self::MODULE_SINGLELAYOUT_FULLVIEW_POST:
                $sidebars = array(
                    self::MODULE_LAYOUT_FULLVIEW => [PoP_Module_Processor_CustomPostLayoutSidebars::class, PoP_Module_Processor_CustomPostLayoutSidebars::MODULE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL],
                    self::MODULE_LAYOUT_FULLVIEW_HIGHLIGHT => [PoP_Module_Processor_CustomPostLayoutSidebars::class, PoP_Module_Processor_CustomPostLayoutSidebars::MODULE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_HIGHLIGHT],
                    self::MODULE_LAYOUT_FULLVIEW_POST => [PoP_Module_Processor_CustomPostLayoutSidebars::class, PoP_Module_Processor_CustomPostLayoutSidebars::MODULE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_POST],
                    self::MODULE_AUTHORLAYOUT_FULLVIEW => [PoP_Module_Processor_CustomPostLayoutSidebars::class, PoP_Module_Processor_CustomPostLayoutSidebars::MODULE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL],
                    self::MODULE_AUTHORLAYOUT_FULLVIEW_HIGHLIGHT => [PoP_Module_Processor_CustomPostLayoutSidebars::class, PoP_Module_Processor_CustomPostLayoutSidebars::MODULE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_HIGHLIGHT],
                    self::MODULE_AUTHORLAYOUT_FULLVIEW_POST => [PoP_Module_Processor_CustomPostLayoutSidebars::class, PoP_Module_Processor_CustomPostLayoutSidebars::MODULE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_POST],
                    self::MODULE_SINGLELAYOUT_FULLVIEW => [PoP_Module_Processor_CustomPostLayoutSidebars::class, PoP_Module_Processor_CustomPostLayoutSidebars::MODULE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL],
                    self::MODULE_SINGLELAYOUT_FULLVIEW_HIGHLIGHT => [PoP_Module_Processor_CustomPostLayoutSidebars::class, PoP_Module_Processor_CustomPostLayoutSidebars::MODULE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_HIGHLIGHT],
                    self::MODULE_SINGLELAYOUT_FULLVIEW_POST => [PoP_Module_Processor_CustomPostLayoutSidebars::class, PoP_Module_Processor_CustomPostLayoutSidebars::MODULE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_POST],
                );

                return $sidebars[$module[1]];
        }

        return parent::getSidebarSubmodule($module);
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        $ret = parent::getImmutableConfiguration($module, $props);

        switch ($module[1]) {
            case self::MODULE_LAYOUT_FULLVIEW_HIGHLIGHT:
            case self::MODULE_AUTHORLAYOUT_FULLVIEW_HIGHLIGHT:
            case self::MODULE_SINGLELAYOUT_FULLVIEW_HIGHLIGHT:
                $ret[GD_JS_CLASSES]['content'] = 'well readable';
                break;
        }
        
        return $ret;
    }
}



