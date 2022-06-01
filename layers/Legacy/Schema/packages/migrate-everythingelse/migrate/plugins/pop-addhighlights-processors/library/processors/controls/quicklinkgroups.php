<?php

class PoP_AddHighlights_Module_Processor_CustomQuicklinkGroups extends PoP_Module_Processor_ControlGroupsBase
{
    public final const COMPONENT_QUICKLINKGROUP_HIGHLIGHTEDIT = 'quicklinkgroup-highlightedit';
    public final const COMPONENT_QUICKLINKGROUP_HIGHLIGHTCONTENT = 'quicklinkgroup-highlightcontent';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_QUICKLINKGROUP_HIGHLIGHTEDIT],
            [self::class, self::COMPONENT_QUICKLINKGROUP_HIGHLIGHTCONTENT],
        );
    }

    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_QUICKLINKGROUP_HIGHLIGHTEDIT:
                $ret[] = [PoP_AddHighlights_Module_Processor_QuicklinkButtonGroups::class, PoP_AddHighlights_Module_Processor_QuicklinkButtonGroups::COMPONENT_QUICKLINKBUTTONGROUP_HIGHLIGHTEDIT];
                $ret[] = [PoP_AddHighlights_Module_Processor_QuicklinkButtonGroups::class, PoP_AddHighlights_Module_Processor_QuicklinkButtonGroups::COMPONENT_QUICKLINKBUTTONGROUP_HIGHLIGHTVIEW];
                break;

            case self::COMPONENT_QUICKLINKGROUP_HIGHLIGHTCONTENT:
                $ret[] = [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::COMPONENT_QUICKLINKGROUP_UPDOWNVOTEUNDOUPDOWNVOTEPOST];
                $ret[] = [PoP_Module_Processor_QuicklinkButtonGroups::class, PoP_Module_Processor_QuicklinkButtonGroups::COMPONENT_QUICKLINKBUTTONGROUP_COMMENTS];
                $ret[] = [PoP_Module_Processor_QuicklinkButtonGroups::class, PoP_Module_Processor_QuicklinkButtonGroups::COMPONENT_QUICKLINKBUTTONGROUP_POSTPERMALINK];
                break;
        }

        return $ret;
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_QUICKLINKGROUP_HIGHLIGHTCONTENT:
                // Make the level below also a 'btn-group' so it shows inline
                $downlevels = array(
                    self::COMPONENT_QUICKLINKGROUP_HIGHLIGHTCONTENT => [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::COMPONENT_QUICKLINKGROUP_UPDOWNVOTEUNDOUPDOWNVOTEPOST],
                );
                $this->appendProp($downlevels[$component->name], $props, 'class', 'btn-group');
                break;
        }

        parent::initModelProps($component, $props);
    }
}


