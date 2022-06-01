<?php

class PoP_ContentPostLinks_Module_Processor_CustomVerticalSingleSidebarInners extends PoP_Module_Processor_SidebarInnersBase
{
    public final const COMPONENT_VERTICALSIDEBARINNER_SINGLE_LINK = 'vertical-sidebarinner-single-link';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_VERTICALSIDEBARINNER_SINGLE_LINK,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getLayoutSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_VERTICALSIDEBARINNER_SINGLE_LINK:
                $ret = array_merge(
                    $ret,
                    PoP_ContentPostLinks_FullViewSidebarSettings::getSidebarSubcomponents(GD_SIDEBARSECTION_POSTLINK)
                );
                break;
        }

        return $ret;
    }
}



