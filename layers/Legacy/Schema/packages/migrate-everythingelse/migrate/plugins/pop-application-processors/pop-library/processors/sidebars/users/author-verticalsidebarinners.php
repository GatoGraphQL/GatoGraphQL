<?php

class PoP_Module_Processor_CustomVerticalAuthorSidebarInners extends PoP_Module_Processor_SidebarInnersBase
{
    public final const COMPONENT_VERTICALSIDEBARINNER_AUTHOR_GENERIC = 'vertical-sidebarinner-author-generic';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_VERTICALSIDEBARINNER_AUTHOR_GENERIC],
        );
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_VERTICALSIDEBARINNER_AUTHOR_GENERIC:
                $ret = array_merge(
                    $ret,
                    FullUserSidebarSettings::getSidebarSubmodules(GD_SIDEBARSECTION_GENERICUSER)
                );
                break;
        }

        return $ret;
    }
}



