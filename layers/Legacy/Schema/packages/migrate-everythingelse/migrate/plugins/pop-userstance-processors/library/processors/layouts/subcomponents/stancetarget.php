<?php

class PoP_Module_Processor_StanceTargetSubcomponentLayouts extends PoP_Module_Processor_StanceTargetSubcomponentLayoutsBase
{
    public final const MODULE_LAYOUT_STANCETARGET_LINE = 'layout-stancetarget-line';
    public final const MODULE_LAYOUT_STANCETARGET_POSTTITLE = 'layout-stancetarget-posttitle';
    public final const MODULE_LAYOUT_STANCETARGET_AUTHORPOSTTITLE = 'layout-stancetarget-authorposttitle';
    public final const MODULE_LAYOUT_STANCETARGET_ADDONS = 'layout-stancetarget-addons';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_STANCETARGET_POSTTITLE],
            [self::class, self::COMPONENT_LAYOUT_STANCETARGET_AUTHORPOSTTITLE],
            [self::class, self::COMPONENT_LAYOUT_STANCETARGET_LINE],
            [self::class, self::COMPONENT_LAYOUT_STANCETARGET_ADDONS],
        );
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_STANCETARGET_POSTTITLE:
                $ret[] = [UserStance_Custom_Module_Processor_Codes::class, UserStance_Custom_Module_Processor_Codes::COMPONENT_CODE_REFERENCEDAFTERREADING];
                $ret[] = [PoP_Module_Processor_CustomFullViewTitleLayouts::class, PoP_Module_Processor_CustomFullViewTitleLayouts::COMPONENT_LAYOUT_POSTTITLE];
                break;

            case self::COMPONENT_LAYOUT_STANCETARGET_AUTHORPOSTTITLE:
                $ret[] = [UserStance_Custom_Module_Processor_Codes::class, UserStance_Custom_Module_Processor_Codes::COMPONENT_CODE_AUTHORREFERENCEDAFTERREADING];
                $ret[] = [PoP_Module_Processor_CustomFullViewTitleLayouts::class, PoP_Module_Processor_CustomFullViewTitleLayouts::COMPONENT_LAYOUT_POSTTITLE];
                break;

            default:
                $layouts = array(
                    self::COMPONENT_LAYOUT_STANCETARGET_LINE => [PoP_Module_Processor_MultiplePostLayouts::class, PoP_Module_Processor_MultiplePostLayouts::COMPONENT_LAYOUT_MULTIPLECONTENT_LINE],
                    self::COMPONENT_LAYOUT_STANCETARGET_ADDONS => [PoP_Module_Processor_MultiplePostLayouts::class, PoP_Module_Processor_MultiplePostLayouts::COMPONENT_LAYOUT_MULTIPLECONTENT_ADDONS],
                );
                if ($layout = $layouts[$component[1]] ?? null) {
                    $ret[] = $layout;
                }
                break;
        }

        return $ret;
    }

    public function getHtmlTag(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_STANCETARGET_POSTTITLE:
            case self::COMPONENT_LAYOUT_STANCETARGET_AUTHORPOSTTITLE:
                return 'span';
        }

        return parent::getHtmlTag($component, $props);
    }
}



