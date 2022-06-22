<?php

class PoP_Module_Processor_StanceTargetSubcomponentLayouts extends PoP_Module_Processor_StanceTargetSubcomponentLayoutsBase
{
    public final const COMPONENT_LAYOUT_STANCETARGET_LINE = 'layout-stancetarget-line';
    public final const COMPONENT_LAYOUT_STANCETARGET_POSTTITLE = 'layout-stancetarget-posttitle';
    public final const COMPONENT_LAYOUT_STANCETARGET_AUTHORPOSTTITLE = 'layout-stancetarget-authorposttitle';
    public final const COMPONENT_LAYOUT_STANCETARGET_ADDONS = 'layout-stancetarget-addons';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_STANCETARGET_POSTTITLE,
            self::COMPONENT_LAYOUT_STANCETARGET_AUTHORPOSTTITLE,
            self::COMPONENT_LAYOUT_STANCETARGET_LINE,
            self::COMPONENT_LAYOUT_STANCETARGET_ADDONS,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getLayoutSubcomponents($component);

        switch ($component->name) {
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
                if ($layout = $layouts[$component->name] ?? null) {
                    $ret[] = $layout;
                }
                break;
        }

        return $ret;
    }

    public function getHtmlTag(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_STANCETARGET_POSTTITLE:
            case self::COMPONENT_LAYOUT_STANCETARGET_AUTHORPOSTTITLE:
                return 'span';
        }

        return parent::getHtmlTag($component, $props);
    }
}



