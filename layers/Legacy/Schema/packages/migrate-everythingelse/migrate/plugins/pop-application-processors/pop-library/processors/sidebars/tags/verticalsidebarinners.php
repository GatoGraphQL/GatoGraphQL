<?php

class Wassup_Module_Processor_CustomVerticalTagSidebarInners extends PoP_Module_Processor_SidebarInnersBase
{
    public final const COMPONENT_VERTICALSIDEBARINNER_TAG = 'vertical-sidebarinner-tag';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_VERTICALSIDEBARINNER_TAG],
        );
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_VERTICALSIDEBARINNER_TAG:
                $ret = array_merge(
                    $ret,
                    FullTagSidebarSettings::getSidebarSubmodules(GD_SIDEBARSECTION_TAG)
                );
                break;
        }

        return $ret;
    }
}



