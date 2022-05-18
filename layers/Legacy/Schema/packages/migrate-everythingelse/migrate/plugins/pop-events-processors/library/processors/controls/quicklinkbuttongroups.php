<?php

class GD_EM_Module_Processor_QuicklinkButtonGroups extends PoP_Module_Processor_ControlButtonGroupsBase
{
    public final const MODULE_EM_QUICKLINKBUTTONGROUP_DOWNLOADLINKSDROPDOWN = 'em-quicklinkbuttongroup-downloadlinksdropdown';
    public final const MODULE_EM_QUICKLINKBUTTONGROUP_DOWNLOADLINKS = 'em-quicklinkbuttongroup-downloadlinks';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_EM_QUICKLINKBUTTONGROUP_DOWNLOADLINKSDROPDOWN],
            [self::class, self::MODULE_EM_QUICKLINKBUTTONGROUP_DOWNLOADLINKS],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_EM_QUICKLINKBUTTONGROUP_DOWNLOADLINKS:
                $ret[] = [GD_EM_Module_Processor_Buttons::class, GD_EM_Module_Processor_Buttons::MODULE_EM_BUTTON_GOOGLECALENDAR];
                $ret[] = [GD_EM_Module_Processor_Buttons::class, GD_EM_Module_Processor_Buttons::MODULE_EM_BUTTON_ICAL];
                break;

            case self::MODULE_EM_QUICKLINKBUTTONGROUP_DOWNLOADLINKSDROPDOWN:
                $ret[] = [GD_EM_Module_Processor_DropdownButtonQuicklinks::class, GD_EM_Module_Processor_DropdownButtonQuicklinks::MODULE_EM_DROPDOWNBUTTONQUICKLINK_DOWNLOADLINKS];
                break;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_EM_QUICKLINKBUTTONGROUP_DOWNLOADLINKS:
                foreach ($this->getSubmodules($module) as $submodule) {
                    $this->appendProp([$submodule], $props, 'class', 'btn btn-link btn-compact');
                }
                break;
        }

        parent::initModelProps($module, $props);
    }
}


