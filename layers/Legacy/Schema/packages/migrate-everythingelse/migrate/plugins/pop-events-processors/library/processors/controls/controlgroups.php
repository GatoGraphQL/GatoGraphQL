<?php
use PoP\Hooks\Facades\HooksAPIFacade;

class PoP_Events_Module_Processor_CustomControlGroups extends PoP_Module_Processor_ControlGroupsBase
{
    public const MODULE_CONTROLGROUP_EVENTLIST = 'controlgroup-eventlist';
    public const MODULE_CONTROLGROUP_BLOCKEVENTLIST = 'controlgroup-blockeventlist';
    public const MODULE_CONTROLGROUP_BLOCKAUTHOREVENTLIST = 'controlgroup-blockauthoreventlist';
    public const MODULE_CONTROLGROUP_BLOCKTAGEVENTLIST = 'controlgroup-blocktageventlist';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CONTROLGROUP_EVENTLIST],
            [self::class, self::MODULE_CONTROLGROUP_BLOCKEVENTLIST],
            [self::class, self::MODULE_CONTROLGROUP_BLOCKAUTHOREVENTLIST],
            [self::class, self::MODULE_CONTROLGROUP_BLOCKTAGEVENTLIST],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_CONTROLGROUP_EVENTLIST:
                $ret[] = [PoP_EventsCreation_Module_Processor_CustomControlButtonGroups::class, PoP_EventsCreation_Module_Processor_CustomControlButtonGroups::MODULE_CONTROLBUTTONGROUP_EVENTLINKS];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_RELOADBLOCKGROUP];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_FILTER];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_RESULTSSHARE];
                break;
                
            case self::MODULE_CONTROLGROUP_BLOCKEVENTLIST:
                $ret[] = [PoP_EventsCreation_Module_Processor_CustomControlButtonGroups::class, PoP_EventsCreation_Module_Processor_CustomControlButtonGroups::MODULE_CONTROLBUTTONGROUP_EVENTLINKS];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_RELOADBLOCK];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_FILTER];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_RESULTSSHARE];
                break;
                
            case self::MODULE_CONTROLGROUP_BLOCKAUTHOREVENTLIST:
                // Allow URE to add the Switch Organization/Organization+Members if the author is an organization
                $layouts = HooksAPIFacade::getInstance()->applyFilters(
                    'GD_EM_Module_Processor_CustomControlGroups:blockauthoreventlist:layouts',
                    array(
                        [PoP_EventsCreation_Module_Processor_CustomControlButtonGroups::class, PoP_EventsCreation_Module_Processor_CustomControlButtonGroups::MODULE_CONTROLBUTTONGROUP_AUTHOREVENTLINKS],
                        [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_RELOADBLOCK],
                        [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_FILTER],
                        [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_RESULTSSHARE]
                    )
                );
                if ($layouts) {
                    $ret = array_merge(
                        $ret,
                        $layouts
                    );
                }
                break;
                
            case self::MODULE_CONTROLGROUP_BLOCKTAGEVENTLIST:
                $ret[] = [PoP_EventsCreation_Module_Processor_CustomControlButtonGroups::class, PoP_EventsCreation_Module_Processor_CustomControlButtonGroups::MODULE_CONTROLBUTTONGROUP_TAGEVENTLINKS];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_RELOADBLOCK];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_FILTER];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_RESULTSSHARE];
                break;
        }

        return $ret;
    }
}


