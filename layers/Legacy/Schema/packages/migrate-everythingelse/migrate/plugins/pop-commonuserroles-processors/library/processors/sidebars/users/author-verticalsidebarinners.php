<?php

class GD_URE_Module_Processor_CustomVerticalAuthorSidebarInners extends PoP_Module_Processor_SidebarInnersBase
{
    public final const COMPONENT_VERTICALSIDEBARINNER_AUTHOR_ORGANIZATION = 'vertical-sidebarinner-author-organization';
    public final const COMPONENT_VERTICALSIDEBARINNER_AUTHOR_INDIVIDUAL = 'vertical-sidebarinner-author-individual';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_VERTICALSIDEBARINNER_AUTHOR_ORGANIZATION,
            self::COMPONENT_VERTICALSIDEBARINNER_AUTHOR_INDIVIDUAL,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getLayoutSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_VERTICALSIDEBARINNER_AUTHOR_ORGANIZATION:
                $ret = array_merge(
                    $ret,
                    URE_FullUserSidebarSettings::getSidebarSubcomponents(GD_SIDEBARSECTION_ORGANIZATION)
                );
                break;

            case self::COMPONENT_VERTICALSIDEBARINNER_AUTHOR_INDIVIDUAL:
                $ret = array_merge(
                    $ret,
                    URE_FullUserSidebarSettings::getSidebarSubcomponents(GD_SIDEBARSECTION_INDIVIDUAL)
                );
                break;
        }

        return $ret;
    }
}



