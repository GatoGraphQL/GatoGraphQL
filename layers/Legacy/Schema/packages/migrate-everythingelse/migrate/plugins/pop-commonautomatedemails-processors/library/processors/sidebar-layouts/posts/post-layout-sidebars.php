<?php

class PoPTheme_Wassup_AE_Module_Processor_CustomPostLayoutSidebars extends PoP_Module_Processor_SidebarsBase
{
    public final const COMPONENT_LAYOUT_AUTOMATEDEMAILS_POSTSIDEBARCOMPACT_HORIZONTAL_POST = 'layout-automatedemails-postsidebarcompact-horizontal-post';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_POSTSIDEBARCOMPACT_HORIZONTAL_POST],

        );
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $sidebarinners = array(
            self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_POSTSIDEBARCOMPACT_HORIZONTAL_POST => [PoPTheme_Wassup_AE_Module_Processor_CustomPostLayoutSidebarInners::class, PoPTheme_Wassup_AE_Module_Processor_CustomPostLayoutSidebarInners::COMPONENT_LAYOUT_AUTOMATEDEMAILS_POSTSIDEBARINNER_COMPACTHORIZONTAL_POST],
        );

        if ($inner = $sidebarinners[$component->name] ?? null) {
            return $inner;
        }

        return parent::getInnerSubcomponent($component);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_POSTSIDEBARCOMPACT_HORIZONTAL_POST:
                $this->appendProp($component, $props, 'class', 'horizontal');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



