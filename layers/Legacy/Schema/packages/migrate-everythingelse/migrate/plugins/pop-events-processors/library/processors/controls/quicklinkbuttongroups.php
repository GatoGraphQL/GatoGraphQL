<?php

class GD_EM_Module_Processor_QuicklinkButtonGroups extends PoP_Module_Processor_ControlButtonGroupsBase
{
    public final const COMPONENT_EM_QUICKLINKBUTTONGROUP_DOWNLOADLINKSDROPDOWN = 'em-quicklinkbuttongroup-downloadlinksdropdown';
    public final const COMPONENT_EM_QUICKLINKBUTTONGROUP_DOWNLOADLINKS = 'em-quicklinkbuttongroup-downloadlinks';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_EM_QUICKLINKBUTTONGROUP_DOWNLOADLINKSDROPDOWN,
            self::COMPONENT_EM_QUICKLINKBUTTONGROUP_DOWNLOADLINKS,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_EM_QUICKLINKBUTTONGROUP_DOWNLOADLINKS:
                $ret[] = [GD_EM_Module_Processor_Buttons::class, GD_EM_Module_Processor_Buttons::COMPONENT_EM_BUTTON_GOOGLECALENDAR];
                $ret[] = [GD_EM_Module_Processor_Buttons::class, GD_EM_Module_Processor_Buttons::COMPONENT_EM_BUTTON_ICAL];
                break;

            case self::COMPONENT_EM_QUICKLINKBUTTONGROUP_DOWNLOADLINKSDROPDOWN:
                $ret[] = [GD_EM_Module_Processor_DropdownButtonQuicklinks::class, GD_EM_Module_Processor_DropdownButtonQuicklinks::COMPONENT_EM_DROPDOWNBUTTONQUICKLINK_DOWNLOADLINKS];
                break;
        }

        return $ret;
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_EM_QUICKLINKBUTTONGROUP_DOWNLOADLINKS:
                foreach ($this->getSubcomponents($component) as $subcomponent) {
                    $this->appendProp([$subcomponent], $props, 'class', 'btn btn-link btn-compact');
                }
                break;
        }

        parent::initModelProps($component, $props);
    }
}


