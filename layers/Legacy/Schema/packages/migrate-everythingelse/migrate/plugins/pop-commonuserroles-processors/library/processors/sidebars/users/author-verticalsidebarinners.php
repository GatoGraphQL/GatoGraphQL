<?php

class GD_URE_Module_Processor_CustomVerticalAuthorSidebarInners extends PoP_Module_Processor_SidebarInnersBase
{
    public final const MODULE_VERTICALSIDEBARINNER_AUTHOR_ORGANIZATION = 'vertical-sidebarinner-author-organization';
    public final const MODULE_VERTICALSIDEBARINNER_AUTHOR_INDIVIDUAL = 'vertical-sidebarinner-author-individual';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_VERTICALSIDEBARINNER_AUTHOR_ORGANIZATION],
            [self::class, self::COMPONENT_VERTICALSIDEBARINNER_AUTHOR_INDIVIDUAL],
        );
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_VERTICALSIDEBARINNER_AUTHOR_ORGANIZATION:
                $ret = array_merge(
                    $ret,
                    URE_FullUserSidebarSettings::getSidebarSubmodules(GD_SIDEBARSECTION_ORGANIZATION)
                );
                break;

            case self::COMPONENT_VERTICALSIDEBARINNER_AUTHOR_INDIVIDUAL:
                $ret = array_merge(
                    $ret,
                    URE_FullUserSidebarSettings::getSidebarSubmodules(GD_SIDEBARSECTION_INDIVIDUAL)
                );
                break;
        }

        return $ret;
    }
}



