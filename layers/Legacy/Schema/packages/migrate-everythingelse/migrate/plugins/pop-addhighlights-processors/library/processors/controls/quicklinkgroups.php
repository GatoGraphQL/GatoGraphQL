<?php

class PoP_AddHighlights_Module_Processor_CustomQuicklinkGroups extends PoP_Module_Processor_ControlGroupsBase
{
    public final const MODULE_QUICKLINKGROUP_HIGHLIGHTEDIT = 'quicklinkgroup-highlightedit';
    public final const MODULE_QUICKLINKGROUP_HIGHLIGHTCONTENT = 'quicklinkgroup-highlightcontent';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_QUICKLINKGROUP_HIGHLIGHTEDIT],
            [self::class, self::MODULE_QUICKLINKGROUP_HIGHLIGHTCONTENT],
        );
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_QUICKLINKGROUP_HIGHLIGHTEDIT:
                $ret[] = [PoP_AddHighlights_Module_Processor_QuicklinkButtonGroups::class, PoP_AddHighlights_Module_Processor_QuicklinkButtonGroups::MODULE_QUICKLINKBUTTONGROUP_HIGHLIGHTEDIT];
                $ret[] = [PoP_AddHighlights_Module_Processor_QuicklinkButtonGroups::class, PoP_AddHighlights_Module_Processor_QuicklinkButtonGroups::MODULE_QUICKLINKBUTTONGROUP_HIGHLIGHTVIEW];
                break;

            case self::MODULE_QUICKLINKGROUP_HIGHLIGHTCONTENT:
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
            case self::MODULE_QUICKLINKGROUP_HIGHLIGHTCONTENT:
                // Make the level below also a 'btn-group' so it shows inline
                $downlevels = array(
                    self::MODULE_QUICKLINKGROUP_HIGHLIGHTCONTENT => [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::MODULE_QUICKLINKGROUP_UPDOWNVOTEUNDOUPDOWNVOTEPOST],
                );
                $this->appendProp($downlevels[$componentVariation[1]], $props, 'class', 'btn-group');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}


