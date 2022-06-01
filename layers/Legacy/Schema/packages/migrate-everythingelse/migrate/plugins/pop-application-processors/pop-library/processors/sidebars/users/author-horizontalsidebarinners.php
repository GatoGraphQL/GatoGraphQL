<?php

class PoP_Module_Processor_CustomHorizontalAuthorSidebarInners extends PoP_Module_Processor_SidebarInnersBase
{
    public final const COMPONENT_HORIZONTALSIDEBARINNER_AUTHOR_GENERIC = 'horizontal-sidebarinner-author-generic';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_HORIZONTALSIDEBARINNER_AUTHOR_GENERIC,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getLayoutSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_HORIZONTALSIDEBARINNER_AUTHOR_GENERIC:
                $ret = array_merge(
                    $ret,
                    FullUserSidebarSettings::getSidebarSubcomponents(GD_SIDEBARSECTION_GENERICUSER)
                );
                break;
        }

        return $ret;
    }

    public function getWrapperClass(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_HORIZONTALSIDEBARINNER_AUTHOR_GENERIC:
                return 'row';
        }
    
        return parent::getWrapperClass($component);
    }
    
    public function getWidgetwrapperClass(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_HORIZONTALSIDEBARINNER_AUTHOR_GENERIC:
                return 'col-xsm-4';
        }
    
        return parent::getWidgetwrapperClass($component);
    }
}



