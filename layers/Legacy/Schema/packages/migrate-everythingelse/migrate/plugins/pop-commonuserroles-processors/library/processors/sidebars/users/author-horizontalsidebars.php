<?php

class GD_URE_Module_Processor_CustomHorizontalAuthorSidebars extends PoP_Module_Processor_SidebarsBase
{
    public final const COMPONENT_HORIZONTALSIDEBAR_AUTHOR_ORGANIZATION = 'horizontal-sidebar-author-organization';
    public final const COMPONENT_HORIZONTALSIDEBAR_AUTHOR_INDIVIDUAL = 'horizontal-sidebar-author-individual';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_HORIZONTALSIDEBAR_AUTHOR_ORGANIZATION],
            [self::class, self::COMPONENT_HORIZONTALSIDEBAR_AUTHOR_INDIVIDUAL],
        );
    }

    public function getInnerSubcomponent(array $component)
    {
        $sidebarinners = array(
            self::COMPONENT_HORIZONTALSIDEBAR_AUTHOR_ORGANIZATION => [GD_URE_Module_Processor_CustomHorizontalAuthorSidebarInners::class, GD_URE_Module_Processor_CustomHorizontalAuthorSidebarInners::COMPONENT_HORIZONTALSIDEBARINNER_AUTHOR_ORGANIZATION],
            self::COMPONENT_HORIZONTALSIDEBAR_AUTHOR_INDIVIDUAL => [GD_URE_Module_Processor_CustomHorizontalAuthorSidebarInners::class, GD_URE_Module_Processor_CustomHorizontalAuthorSidebarInners::COMPONENT_HORIZONTALSIDEBARINNER_AUTHOR_INDIVIDUAL],
        );

        if ($inner = $sidebarinners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubcomponent($component);
    }
}



