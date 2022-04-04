<?php

class GD_URE_Module_Processor_MembersLayoutMultipleComponents extends PoP_Module_Processor_MultiplesBase
{
    public final const MODULE_URE_MULTICOMPONENT_COMMUNITYMEMBERS = 'ure-multicomponent-communitymembers';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_URE_MULTICOMPONENT_COMMUNITYMEMBERS],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_URE_MULTICOMPONENT_COMMUNITYMEMBERS:
                $ret[] = [GD_URE_Module_Processor_MembersLayouts::class, GD_URE_Module_Processor_MembersLayouts::MODULE_URE_LAYOUT_COMMUNITYMEMBERS];
                break;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_URE_MULTICOMPONENT_COMMUNITYMEMBERS:
                $this->appendProp($module, $props, 'class', 'clearfix');
                $this->appendProp([GD_URE_Module_Processor_MembersLayouts::class, GD_URE_Module_Processor_MembersLayouts::MODULE_URE_LAYOUT_COMMUNITYMEMBERS], $props, 'class', 'pull-left');
                break;
        }

        parent::initModelProps($module, $props);
    }
}



