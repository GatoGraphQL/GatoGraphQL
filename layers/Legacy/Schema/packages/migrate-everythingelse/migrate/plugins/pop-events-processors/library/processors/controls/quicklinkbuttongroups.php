<?php

class GD_EM_Module_Processor_QuicklinkButtonGroups extends PoP_Module_Processor_ControlButtonGroupsBase
{
    public final const MODULE_EM_QUICKLINKBUTTONGROUP_DOWNLOADLINKSDROPDOWN = 'em-quicklinkbuttongroup-downloadlinksdropdown';
    public final const MODULE_EM_QUICKLINKBUTTONGROUP_DOWNLOADLINKS = 'em-quicklinkbuttongroup-downloadlinks';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_EM_QUICKLINKBUTTONGROUP_DOWNLOADLINKSDROPDOWN],
            [self::class, self::COMPONENT_EM_QUICKLINKBUTTONGROUP_DOWNLOADLINKS],
        );
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);

        switch ($component[1]) {
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

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_EM_QUICKLINKBUTTONGROUP_DOWNLOADLINKS:
                foreach ($this->getSubComponents($component) as $subComponent) {
                    $this->appendProp([$subComponent], $props, 'class', 'btn btn-link btn-compact');
                }
                break;
        }

        parent::initModelProps($component, $props);
    }
}


