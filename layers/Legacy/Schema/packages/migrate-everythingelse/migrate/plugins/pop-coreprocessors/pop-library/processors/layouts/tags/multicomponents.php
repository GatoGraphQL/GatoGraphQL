<?php

class PoP_Module_ProcessorTagMultipleComponents extends PoP_Module_Processor_MultiplesBase
{
    public final const COMPONENT_LAYOUT_TAG_DETAILS = 'multicomponent-tag';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_TAG_DETAILS],
        );
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);

        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_TAG_DETAILS:
                $ret[] = [PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::COMPONENT_QUICKLINKGROUP_TAG];
                $ret[] = [PoP_Module_Processor_TagLayouts::class, PoP_Module_Processor_TagLayouts::COMPONENT_LAYOUT_TAGH4];
                $ret[] = [GD_SocialNetwork_Module_Processor_QuicklinkButtonGroups::class, GD_SocialNetwork_Module_Processor_QuicklinkButtonGroups::COMPONENT_QUICKLINKBUTTONGROUP_TAGSUBSCRIBETOUNSUBSCRIBEFROM];
                break;
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_TAG_DETAILS:
                $this->appendProp($component, $props, 'class', 'layout');
                $this->appendProp([PoP_Module_Processor_CustomQuicklinkGroups::class, PoP_Module_Processor_CustomQuicklinkGroups::COMPONENT_QUICKLINKGROUP_TAG], $props, 'class', 'quicklinkgroup quicklinkgroup-top icon-only pull-right');
                $this->appendProp([PoP_Module_Processor_TagLayouts::class, PoP_Module_Processor_TagLayouts::COMPONENT_LAYOUT_TAGH4], $props, 'class', 'layout-tag-details');
                break;
        }

        parent::initModelProps($component, $props);
    }
}

