<?php

class UserStance_Module_Processor_CustomQuicklinkGroups extends PoP_Module_Processor_ControlGroupsBase
{
    public final const MODULE_QUICKLINKGROUP_STANCEEDIT = 'quicklinkgroup-stanceedit';
    public final const MODULE_QUICKLINKGROUP_STANCECONTENT = 'quicklinkgroup-stancecontent';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_QUICKLINKGROUP_STANCEEDIT],
            [self::class, self::MODULE_QUICKLINKGROUP_STANCECONTENT],
        );
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_QUICKLINKGROUP_STANCEEDIT:
                $ret[] = [UserStance_Module_Processor_QuicklinkButtonGroups::class, UserStance_Module_Processor_QuicklinkButtonGroups::MODULE_QUICKLINKBUTTONGROUP_STANCEEDIT];
                $ret[] = [UserStance_Module_Processor_QuicklinkButtonGroups::class, UserStance_Module_Processor_QuicklinkButtonGroups::MODULE_QUICKLINKBUTTONGROUP_STANCEVIEW];
                break;

            case self::MODULE_QUICKLINKGROUP_STANCECONTENT:
                $ret[] = [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::MODULE_QUICKLINKGROUP_UPDOWNVOTEUNDOUPDOWNVOTEPOST];
                $ret[] = [PoP_Module_Processor_QuicklinkButtonGroups::class, PoP_Module_Processor_QuicklinkButtonGroups::MODULE_QUICKLINKBUTTONGROUP_COMMENTS];
                $ret[] = [PoP_Module_Processor_QuicklinkButtonGroups::class, PoP_Module_Processor_QuicklinkButtonGroups::MODULE_QUICKLINKBUTTONGROUP_POSTPERMALINK];
                break;
        }

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_QUICKLINKGROUP_STANCECONTENT:
                // Make the level below also a 'btn-group' so it shows inline
                $downlevels = array(
                    self::MODULE_QUICKLINKGROUP_STANCECONTENT => [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::MODULE_QUICKLINKGROUP_UPDOWNVOTEUNDOUPDOWNVOTEPOST],
                );
                // $this->appendProp($downlevels[$componentVariation[1]], $props, 'class', 'btn-group bg-warning');
                $this->appendProp($downlevels[$componentVariation[1]], $props, 'class', 'btn-group');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}


