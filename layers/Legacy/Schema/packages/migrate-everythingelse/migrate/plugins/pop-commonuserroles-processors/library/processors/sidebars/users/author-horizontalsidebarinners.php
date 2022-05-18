<?php

class GD_URE_Module_Processor_CustomHorizontalAuthorSidebarInners extends PoP_Module_Processor_SidebarInnersBase
{
    public final const COMPONENT_HORIZONTALSIDEBARINNER_AUTHOR_ORGANIZATION = 'horizontal-sidebarinner-author-organization';
    public final const COMPONENT_HORIZONTALSIDEBARINNER_AUTHOR_INDIVIDUAL = 'horizontal-sidebarinner-author-individual';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_HORIZONTALSIDEBARINNER_AUTHOR_ORGANIZATION],
            [self::class, self::COMPONENT_HORIZONTALSIDEBARINNER_AUTHOR_INDIVIDUAL],
        );
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_HORIZONTALSIDEBARINNER_AUTHOR_ORGANIZATION:
                $ret = array_merge(
                    $ret,
                    URE_FullUserSidebarSettings::getSidebarSubmodules(GD_SIDEBARSECTION_ORGANIZATION)
                );
                break;

            case self::COMPONENT_HORIZONTALSIDEBARINNER_AUTHOR_INDIVIDUAL:
                $ret = array_merge(
                    $ret,
                    URE_FullUserSidebarSettings::getSidebarSubmodules(GD_SIDEBARSECTION_INDIVIDUAL)
                );
                break;
        }

        return $ret;
    }

    public function getWrapperClass(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_HORIZONTALSIDEBARINNER_AUTHOR_ORGANIZATION:
            case self::COMPONENT_HORIZONTALSIDEBARINNER_AUTHOR_INDIVIDUAL:
                return 'row';
        }
    
        return parent::getWrapperClass($component);
    }
    
    public function getWidgetwrapperClass(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_HORIZONTALSIDEBARINNER_AUTHOR_ORGANIZATION:
            case self::COMPONENT_HORIZONTALSIDEBARINNER_AUTHOR_INDIVIDUAL:
                return 'col-xsm-4';
        }
    
        return parent::getWidgetwrapperClass($component);
    }
}



