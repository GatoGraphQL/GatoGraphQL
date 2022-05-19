<?php

class Wassup_Module_Processor_CustomVerticalSingleSidebarInners extends PoP_Module_Processor_SidebarInnersBase
{
    public final const COMPONENT_VERTICALSIDEBARINNER_SINGLE_HIGHLIGHT = 'vertical-sidebarinner-single-highlight';
    public final const COMPONENT_VERTICALSIDEBARINNER_SINGLE_POST = 'vertical-sidebarinner-single-post';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_VERTICALSIDEBARINNER_SINGLE_HIGHLIGHT],
            [self::class, self::COMPONENT_VERTICALSIDEBARINNER_SINGLE_POST],
        );
    }

    public function getLayoutSubcomponents(array $component)
    {
        $ret = parent::getLayoutSubcomponents($component);

        switch ($component[1]) {
            case self::COMPONENT_VERTICALSIDEBARINNER_SINGLE_HIGHLIGHT:
                $ret = array_merge(
                    $ret,
                    FullViewSidebarSettings::getSidebarSubcomponents(GD_SIDEBARSECTION_HIGHLIGHT)
                );
                break;

            case self::COMPONENT_VERTICALSIDEBARINNER_SINGLE_POST:
                $ret = array_merge(
                    $ret,
                    FullViewSidebarSettings::getSidebarSubcomponents(GD_SIDEBARSECTION_POST)
                );
                break;
        }

        return $ret;
    }
}



