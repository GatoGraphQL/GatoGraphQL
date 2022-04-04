<?php
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;

class UserStance_Module_Processor_CustomFullViewLayouts extends PoP_Module_Processor_CustomFullViewLayoutsBase
{
    public final const MODULE_LAYOUT_FULLVIEW_STANCE = 'layout-fullview-stance';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_FULLVIEW_STANCE],
        );
    }

    public function getFooterSubmodules(array $module)
    {
        $ret = parent::getFooterSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_LAYOUT_FULLVIEW_STANCE:
                $ret[] = [PoP_Module_Processor_ViewComponentButtonWrappers::class, PoP_Module_Processor_ViewComponentButtonWrappers::MODULE_LAYOUTWRAPPER_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL];
                $ret[] = [UserStance_Module_Processor_CustomWrapperLayouts::class, UserStance_Module_Processor_CustomWrapperLayouts::MODULE_LAYOUTWRAPPER_USERSTANCEPOSTINTERACTION];
                $ret[] = [PoP_Module_Processor_CustomWrapperLayouts::class, PoP_Module_Processor_CustomWrapperLayouts::MODULE_CODEWRAPPER_LAZYLOADINGSPINNER];
                $ret[] = [PoP_Module_Processor_PostCommentSubcomponentLayouts::class, PoP_Module_Processor_PostCommentSubcomponentLayouts::MODULE_LAZYSUBCOMPONENT_NOHEADERPOSTCOMMENTS];
                break;
        }

        return $ret;
    }

    public function getSidebarSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_FULLVIEW_STANCE:
                $sidebars = array(
                    self::MODULE_LAYOUT_FULLVIEW_STANCE => [UserStance_Module_Processor_CustomPostLayoutSidebars::class, UserStance_Module_Processor_CustomPostLayoutSidebars::MODULE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_STANCE],
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
            case self::MODULE_LAYOUT_FULLVIEW_STANCE:
                $ret[GD_JS_CLASSES]['content'] = 'alert alert-stance';
                $ret[GD_JS_CLASSES]['content-inner'] = 'readable';
                break;
        }
        
        return $ret;
    }

    public function getAbovecontentSubmodules(array $module)
    {
        $ret = parent::getAbovecontentSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_LAYOUT_FULLVIEW_STANCE:
                $ret[] = [UserStance_Module_Processor_Layouts::class, UserStance_Module_Processor_Layouts::MODULE_LAYOUTSTANCE];
                break;
        }

        return $ret;
    }
}



