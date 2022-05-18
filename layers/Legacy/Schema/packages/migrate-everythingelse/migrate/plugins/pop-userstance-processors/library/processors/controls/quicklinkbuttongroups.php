<?php

class UserStance_Module_Processor_QuicklinkButtonGroups extends PoP_Module_Processor_ControlButtonGroupsBase
{
    public final const MODULE_QUICKLINKBUTTONGROUP_STANCEEDIT = 'quicklinkbuttongroup-stanceedit';
    public final const MODULE_QUICKLINKBUTTONGROUP_STANCEVIEW = 'quicklinkbuttongroup-stanceview';
    public final const MODULE_QUICKLINKBUTTONGROUP_POSTSTANCE = 'quicklinkbuttongroup-poststance';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_QUICKLINKBUTTONGROUP_STANCEEDIT],
            [self::class, self::MODULE_QUICKLINKBUTTONGROUP_STANCEVIEW],
            [self::class, self::MODULE_QUICKLINKBUTTONGROUP_POSTSTANCE],
        );
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);

        switch ($component[1]) {
            case self::MODULE_QUICKLINKBUTTONGROUP_STANCEEDIT:
                $ret[] = [UserStance_Module_Processor_Buttons::class, UserStance_Module_Processor_Buttons::MODULE_BUTTON_STANCEEDIT];
                break;

            case self::MODULE_QUICKLINKBUTTONGROUP_STANCEVIEW:
                $ret[] = [UserStance_Module_Processor_ButtonWrappers::class, UserStance_Module_Processor_ButtonWrappers::MODULE_BUTTONWRAPPER_STANCEVIEW];
                break;

            case self::MODULE_QUICKLINKBUTTONGROUP_POSTSTANCE:
                $ret[] = [UserStance_Custom_Module_Processor_Codes::class, UserStance_Custom_Module_Processor_Codes::MODULE_CODE_POSTSTANCE];
                $ret[] = [UserStance_Module_Processor_Buttons::class, UserStance_Module_Processor_Buttons::MODULE_BUTTON_POSTSTANCES_PRO];
                $ret[] = [UserStance_Module_Processor_Buttons::class, UserStance_Module_Processor_Buttons::MODULE_BUTTON_POSTSTANCES_NEUTRAL];
                $ret[] = [UserStance_Module_Processor_Buttons::class, UserStance_Module_Processor_Buttons::MODULE_BUTTON_POSTSTANCES_AGAINST];
                break;
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::MODULE_QUICKLINKBUTTONGROUP_POSTSTANCE:
                $this->appendProp($component, $props, 'class', 'pop-stance-count');
                break;
        }

        parent::initModelProps($component, $props);
    }
}


