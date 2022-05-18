<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

class UserStance_Module_Processor_CustomFullViewLayouts extends PoP_Module_Processor_CustomFullViewLayoutsBase
{
    public final const MODULE_LAYOUT_FULLVIEW_STANCE = 'layout-fullview-stance';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_FULLVIEW_STANCE],
        );
    }

    public function getFooterSubmodules(array $component)
    {
        $ret = parent::getFooterSubmodules($component);

        switch ($component[1]) {
            case self::MODULE_LAYOUT_FULLVIEW_STANCE:
                $ret[] = [PoP_Module_Processor_ViewComponentButtonWrappers::class, PoP_Module_Processor_ViewComponentButtonWrappers::MODULE_LAYOUTWRAPPER_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL];
                $ret[] = [UserStance_Module_Processor_CustomWrapperLayouts::class, UserStance_Module_Processor_CustomWrapperLayouts::MODULE_LAYOUTWRAPPER_USERSTANCEPOSTINTERACTION];
                $ret[] = [PoP_Module_Processor_CustomWrapperLayouts::class, PoP_Module_Processor_CustomWrapperLayouts::MODULE_CODEWRAPPER_LAZYLOADINGSPINNER];
                $ret[] = [PoP_Module_Processor_PostCommentSubcomponentLayouts::class, PoP_Module_Processor_PostCommentSubcomponentLayouts::MODULE_LAZYSUBCOMPONENT_NOHEADERPOSTCOMMENTS];
                break;
        }

        return $ret;
    }

    public function getSidebarSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_LAYOUT_FULLVIEW_STANCE:
                $sidebars = array(
                    self::MODULE_LAYOUT_FULLVIEW_STANCE => [UserStance_Module_Processor_CustomPostLayoutSidebars::class, UserStance_Module_Processor_CustomPostLayoutSidebars::MODULE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_STANCE],
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
            case self::MODULE_LAYOUT_FULLVIEW_STANCE:
                $ret[GD_JS_CLASSES]['content'] = 'alert alert-stance';
                $ret[GD_JS_CLASSES]['content-inner'] = 'readable';
                break;
        }
        
        return $ret;
    }

    public function getAbovecontentSubmodules(array $component)
    {
        $ret = parent::getAbovecontentSubmodules($component);

        switch ($component[1]) {
            case self::MODULE_LAYOUT_FULLVIEW_STANCE:
                $ret[] = [UserStance_Module_Processor_Layouts::class, UserStance_Module_Processor_Layouts::MODULE_LAYOUTSTANCE];
                break;
        }

        return $ret;
    }
}



