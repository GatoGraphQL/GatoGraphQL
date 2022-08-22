<?php

class PoP_UserCommunities_EM_ComponentProcessor_CustomScrollMaps extends PoP_Module_Processor_ScrollMapsBase
{
    public final const COMPONENT_SCROLL_COMMUNITIES_MAP = 'scroll-communities-map';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_SCROLL_COMMUNITIES_MAP,
        );
    }


    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $inners = array(
            self::COMPONENT_SCROLL_COMMUNITIES_MAP => [PoP_UserCommunities_EM_Module_Processor_CustomScrollInners::class, PoP_UserCommunities_EM_Module_Processor_CustomScrollInners::COMPONENT_SCROLLINNER_COMMUNITIES_MAP],
        );

        if ($inner = $inners[$component->name] ?? null) {
            return $inner;
        }

        return parent::getInnerSubcomponent($component);
    }
}

