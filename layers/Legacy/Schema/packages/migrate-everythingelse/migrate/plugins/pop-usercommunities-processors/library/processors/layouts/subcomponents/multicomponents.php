<?php

class GD_URE_Module_Processor_MembersLayoutMultipleComponents extends PoP_Module_Processor_MultiplesBase
{
    public final const COMPONENT_URE_MULTICOMPONENT_COMMUNITYMEMBERS = 'ure-multicomponent-communitymembers';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_URE_MULTICOMPONENT_COMMUNITYMEMBERS,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_URE_MULTICOMPONENT_COMMUNITYMEMBERS:
                $ret[] = [GD_URE_Module_Processor_MembersLayouts::class, GD_URE_Module_Processor_MembersLayouts::COMPONENT_URE_LAYOUT_COMMUNITYMEMBERS];
                break;
        }

        return $ret;
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_URE_MULTICOMPONENT_COMMUNITYMEMBERS:
                $this->appendProp($component, $props, 'class', 'clearfix');
                $this->appendProp([GD_URE_Module_Processor_MembersLayouts::class, GD_URE_Module_Processor_MembersLayouts::COMPONENT_URE_LAYOUT_COMMUNITYMEMBERS], $props, 'class', 'pull-left');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



