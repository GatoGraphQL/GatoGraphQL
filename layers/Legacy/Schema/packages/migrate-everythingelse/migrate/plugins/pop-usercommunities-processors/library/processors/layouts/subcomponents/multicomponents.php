<?php

class GD_URE_Module_Processor_MembersLayoutMultipleComponents extends PoP_Module_Processor_MultiplesBase
{
    public final const MODULE_URE_MULTICOMPONENT_COMMUNITYMEMBERS = 'ure-multicomponent-communitymembers';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_URE_MULTICOMPONENT_COMMUNITYMEMBERS],
        );
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);

        switch ($component[1]) {
            case self::COMPONENT_URE_MULTICOMPONENT_COMMUNITYMEMBERS:
                $ret[] = [GD_URE_Module_Processor_MembersLayouts::class, GD_URE_Module_Processor_MembersLayouts::COMPONENT_URE_LAYOUT_COMMUNITYMEMBERS];
                break;
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_URE_MULTICOMPONENT_COMMUNITYMEMBERS:
                $this->appendProp($component, $props, 'class', 'clearfix');
                $this->appendProp([GD_URE_Module_Processor_MembersLayouts::class, GD_URE_Module_Processor_MembersLayouts::COMPONENT_URE_LAYOUT_COMMUNITYMEMBERS], $props, 'class', 'pull-left');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



