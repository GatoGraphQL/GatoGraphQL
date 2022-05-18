<?php

class UserStance_Module_Processor_CustomQuicklinkGroups extends PoP_Module_Processor_ControlGroupsBase
{
    public final const MODULE_QUICKLINKGROUP_STANCEEDIT = 'quicklinkgroup-stanceedit';
    public final const MODULE_QUICKLINKGROUP_STANCECONTENT = 'quicklinkgroup-stancecontent';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_QUICKLINKGROUP_STANCEEDIT],
            [self::class, self::COMPONENT_QUICKLINKGROUP_STANCECONTENT],
        );
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);

        switch ($component[1]) {
            case self::COMPONENT_QUICKLINKGROUP_STANCEEDIT:
                $ret[] = [UserStance_Module_Processor_QuicklinkButtonGroups::class, UserStance_Module_Processor_QuicklinkButtonGroups::COMPONENT_QUICKLINKBUTTONGROUP_STANCEEDIT];
                $ret[] = [UserStance_Module_Processor_QuicklinkButtonGroups::class, UserStance_Module_Processor_QuicklinkButtonGroups::COMPONENT_QUICKLINKBUTTONGROUP_STANCEVIEW];
                break;

            case self::COMPONENT_QUICKLINKGROUP_STANCECONTENT:
                $ret[] = [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::COMPONENT_QUICKLINKGROUP_UPDOWNVOTEUNDOUPDOWNVOTEPOST];
                $ret[] = [PoP_Module_Processor_QuicklinkButtonGroups::class, PoP_Module_Processor_QuicklinkButtonGroups::COMPONENT_QUICKLINKBUTTONGROUP_COMMENTS];
                $ret[] = [PoP_Module_Processor_QuicklinkButtonGroups::class, PoP_Module_Processor_QuicklinkButtonGroups::COMPONENT_QUICKLINKBUTTONGROUP_POSTPERMALINK];
                break;
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_QUICKLINKGROUP_STANCECONTENT:
                // Make the level below also a 'btn-group' so it shows inline
                $downlevels = array(
                    self::COMPONENT_QUICKLINKGROUP_STANCECONTENT => [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::COMPONENT_QUICKLINKGROUP_UPDOWNVOTEUNDOUPDOWNVOTEPOST],
                );
                // $this->appendProp($downlevels[$component[1]], $props, 'class', 'btn-group bg-warning');
                $this->appendProp($downlevels[$component[1]], $props, 'class', 'btn-group');
                break;
        }

        parent::initModelProps($component, $props);
    }
}


