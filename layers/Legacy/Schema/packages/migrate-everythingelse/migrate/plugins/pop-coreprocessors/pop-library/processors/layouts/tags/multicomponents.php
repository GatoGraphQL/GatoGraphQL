<?php

class PoP_Module_ProcessorTagMultipleComponents extends PoP_Module_Processor_MultiplesBase
{
    public final const MODULE_LAYOUT_TAG_DETAILS = 'multicomponent-tag';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_TAG_DETAILS],
        );
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_TAG_DETAILS:
                $ret[] = [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::MODULE_QUICKLINKGROUP_TAG];
                $ret[] = [PoP_Module_Processor_TagLayouts::class, PoP_Module_Processor_TagLayouts::MODULE_LAYOUT_TAGH4];
                $ret[] = [GD_SocialNetwork_Module_Processor_QuicklinkButtonGroups::class, GD_SocialNetwork_Module_Processor_QuicklinkButtonGroups::MODULE_QUICKLINKBUTTONGROUP_TAGSUBSCRIBETOUNSUBSCRIBEFROM];
                break;
        }

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_TAG_DETAILS:
                $this->appendProp($componentVariation, $props, 'class', 'layout');
                $this->appendProp([PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::MODULE_QUICKLINKGROUP_TAG], $props, 'class', 'quicklinkgroup quicklinkgroup-top icon-only pull-right');
                $this->appendProp([PoP_Module_Processor_TagLayouts::class, PoP_Module_Processor_TagLayouts::MODULE_LAYOUT_TAGH4], $props, 'class', 'layout-tag-details');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}

