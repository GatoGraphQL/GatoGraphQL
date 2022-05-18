<?php

class GD_URE_Module_Processor_MembersLayoutMultipleComponents extends PoP_Module_Processor_MultiplesBase
{
    public final const MODULE_URE_MULTICOMPONENT_COMMUNITYMEMBERS = 'ure-multicomponent-communitymembers';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_URE_MULTICOMPONENT_COMMUNITYMEMBERS],
        );
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_URE_MULTICOMPONENT_COMMUNITYMEMBERS:
                $ret[] = [GD_URE_Module_Processor_MembersLayouts::class, GD_URE_Module_Processor_MembersLayouts::MODULE_URE_LAYOUT_COMMUNITYMEMBERS];
                break;
        }

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_URE_MULTICOMPONENT_COMMUNITYMEMBERS:
                $this->appendProp($componentVariation, $props, 'class', 'clearfix');
                $this->appendProp([GD_URE_Module_Processor_MembersLayouts::class, GD_URE_Module_Processor_MembersLayouts::MODULE_URE_LAYOUT_COMMUNITYMEMBERS], $props, 'class', 'pull-left');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



