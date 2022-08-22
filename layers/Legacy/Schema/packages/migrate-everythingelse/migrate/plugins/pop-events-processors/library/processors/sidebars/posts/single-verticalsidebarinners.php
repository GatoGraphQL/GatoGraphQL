<?php

class GD_EM_Module_Processor_CustomVerticalSingleSidebarInners extends PoP_Module_Processor_SidebarInnersBase
{
    public final const COMPONENT_VERTICALSIDEBARINNER_SINGLE_EVENT = 'vertical-sidebarinner-single-event';
    public final const COMPONENT_VERTICALSIDEBARINNER_SINGLE_PASTEVENT = 'vertical-sidebarinner-single-pastevent';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_VERTICALSIDEBARINNER_SINGLE_EVENT,
            self::COMPONENT_VERTICALSIDEBARINNER_SINGLE_PASTEVENT,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getLayoutSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_VERTICALSIDEBARINNER_SINGLE_EVENT:
                $ret = array_merge(
                    $ret,
                    EM_FullViewSidebarSettings::getSidebarSubcomponents(GD_SIDEBARSECTION_EVENT)
                );
                break;

            case self::COMPONENT_VERTICALSIDEBARINNER_SINGLE_PASTEVENT:
                $ret = array_merge(
                    $ret,
                    EM_FullViewSidebarSettings::getSidebarSubcomponents(GD_SIDEBARSECTION_PASTEVENT)
                );
                break;
        }

        return $ret;
    }
}



