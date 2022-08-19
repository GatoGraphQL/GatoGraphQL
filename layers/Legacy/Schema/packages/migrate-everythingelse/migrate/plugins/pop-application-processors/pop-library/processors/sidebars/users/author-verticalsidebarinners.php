<?php

class PoP_Module_Processor_CustomVerticalAuthorSidebarInners extends PoP_Module_Processor_SidebarInnersBase
{
    public final const COMPONENT_VERTICALSIDEBARINNER_AUTHOR_GENERIC = 'vertical-sidebarinner-author-generic';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_VERTICALSIDEBARINNER_AUTHOR_GENERIC,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getLayoutSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_VERTICALSIDEBARINNER_AUTHOR_GENERIC:
                $ret = array_merge(
                    $ret,
                    FullUserSidebarSettings::getSidebarSubcomponents(GD_SIDEBARSECTION_GENERICUSER)
                );
                break;
        }

        return $ret;
    }
}



