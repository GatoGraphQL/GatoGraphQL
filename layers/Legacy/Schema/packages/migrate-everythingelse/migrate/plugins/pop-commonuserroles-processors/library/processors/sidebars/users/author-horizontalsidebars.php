<?php

class GD_URE_Module_Processor_CustomHorizontalAuthorSidebars extends PoP_Module_Processor_SidebarsBase
{
    public final const COMPONENT_HORIZONTALSIDEBAR_AUTHOR_ORGANIZATION = 'horizontal-sidebar-author-organization';
    public final const COMPONENT_HORIZONTALSIDEBAR_AUTHOR_INDIVIDUAL = 'horizontal-sidebar-author-individual';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_HORIZONTALSIDEBAR_AUTHOR_ORGANIZATION,
            self::COMPONENT_HORIZONTALSIDEBAR_AUTHOR_INDIVIDUAL,
        );
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $sidebarinners = array(
            self::COMPONENT_HORIZONTALSIDEBAR_AUTHOR_ORGANIZATION => [GD_URE_Module_Processor_CustomHorizontalAuthorSidebarInners::class, GD_URE_Module_Processor_CustomHorizontalAuthorSidebarInners::COMPONENT_HORIZONTALSIDEBARINNER_AUTHOR_ORGANIZATION],
            self::COMPONENT_HORIZONTALSIDEBAR_AUTHOR_INDIVIDUAL => [GD_URE_Module_Processor_CustomHorizontalAuthorSidebarInners::class, GD_URE_Module_Processor_CustomHorizontalAuthorSidebarInners::COMPONENT_HORIZONTALSIDEBARINNER_AUTHOR_INDIVIDUAL],
        );

        if ($inner = $sidebarinners[$component->name] ?? null) {
            return $inner;
        }

        return parent::getInnerSubcomponent($component);
    }
}



