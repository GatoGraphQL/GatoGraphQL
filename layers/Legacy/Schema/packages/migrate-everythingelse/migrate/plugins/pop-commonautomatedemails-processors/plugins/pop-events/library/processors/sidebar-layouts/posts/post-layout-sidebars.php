<?php

class PoPTheme_Wassup_EM_AE_Module_Processor_CustomPostLayoutSidebars extends PoP_Module_Processor_SidebarsBase
{
    public final const MODULE_LAYOUT_AUTOMATEDEMAILS_POSTSIDEBARCOMPACT_HORIZONTAL_EVENT = 'layout-automatedemails-postsidebarcompact-horizontal-event';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_AUTOMATEDEMAILS_POSTSIDEBARCOMPACT_HORIZONTAL_EVENT],

        );
    }

    public function getInnerSubmodule(array $component)
    {
        $sidebarinners = array(
            self::MODULE_LAYOUT_AUTOMATEDEMAILS_POSTSIDEBARCOMPACT_HORIZONTAL_EVENT => [PoPTheme_Wassup_EM_AE_Module_Processor_CustomPostLayoutSidebarInners::class, PoPTheme_Wassup_EM_AE_Module_Processor_CustomPostLayoutSidebarInners::MODULE_LAYOUT_AUTOMATEDEMAILS_POSTSIDEBARINNER_COMPACTHORIZONTAL_EVENT],
        );

        if ($inner = $sidebarinners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($component);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::MODULE_LAYOUT_AUTOMATEDEMAILS_POSTSIDEBARCOMPACT_HORIZONTAL_EVENT:

                $this->appendProp($component, $props, 'class', 'horizontal');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



