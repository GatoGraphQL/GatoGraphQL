<?php

class PoP_Module_Processor_StanceTargetSubcomponentLayouts extends PoP_Module_Processor_StanceTargetSubcomponentLayoutsBase
{
    public const MODULE_LAYOUT_STANCETARGET_LINE = 'layout-stancetarget-line';
    public const MODULE_LAYOUT_STANCETARGET_POSTTITLE = 'layout-stancetarget-posttitle';
    public const MODULE_LAYOUT_STANCETARGET_AUTHORPOSTTITLE = 'layout-stancetarget-authorposttitle';
    public const MODULE_LAYOUT_STANCETARGET_ADDONS = 'layout-stancetarget-addons';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_STANCETARGET_POSTTITLE],
            [self::class, self::MODULE_LAYOUT_STANCETARGET_AUTHORPOSTTITLE],
            [self::class, self::MODULE_LAYOUT_STANCETARGET_LINE],
            [self::class, self::MODULE_LAYOUT_STANCETARGET_ADDONS],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);
    
        switch ($module[1]) {
            case self::MODULE_LAYOUT_STANCETARGET_POSTTITLE:
                $ret[] = [UserStance_Custom_Module_Processor_Codes::class, UserStance_Custom_Module_Processor_Codes::MODULE_CODE_REFERENCEDAFTERREADING];
                $ret[] = [PoP_Module_Processor_CustomFullViewTitleLayouts::class, PoP_Module_Processor_CustomFullViewTitleLayouts::MODULE_LAYOUT_POSTTITLE];
                break;
            
            case self::MODULE_LAYOUT_STANCETARGET_AUTHORPOSTTITLE:
                $ret[] = [UserStance_Custom_Module_Processor_Codes::class, UserStance_Custom_Module_Processor_Codes::MODULE_CODE_AUTHORREFERENCEDAFTERREADING];
                $ret[] = [PoP_Module_Processor_CustomFullViewTitleLayouts::class, PoP_Module_Processor_CustomFullViewTitleLayouts::MODULE_LAYOUT_POSTTITLE];
                break;
            
            default:
                $layouts = array(
                    self::MODULE_LAYOUT_STANCETARGET_LINE => [PoP_Module_Processor_MultiplePostLayouts::class, PoP_Module_Processor_MultiplePostLayouts::MODULE_LAYOUT_MULTIPLECONTENT_LINE],
                    self::MODULE_LAYOUT_STANCETARGET_ADDONS => [PoP_Module_Processor_MultiplePostLayouts::class, PoP_Module_Processor_MultiplePostLayouts::MODULE_LAYOUT_MULTIPLECONTENT_ADDONS],
                );
                if ($layout = $layouts[$module[1]]) {
                    $ret[] = $layout;
                }
                break;
        }

        return $ret;
    }

    public function getHtmlTag(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_LAYOUT_STANCETARGET_POSTTITLE:
            case self::MODULE_LAYOUT_STANCETARGET_AUTHORPOSTTITLE:
                return 'span';
        }
    
        return parent::getHtmlTag($module, $props);
    }
}



