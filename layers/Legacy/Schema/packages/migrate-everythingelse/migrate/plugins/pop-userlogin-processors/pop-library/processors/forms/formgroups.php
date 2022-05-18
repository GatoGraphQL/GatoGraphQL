<?php

class PoP_Module_Processor_LoginFormGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public final const COMPONENT_FORMINPUTGROUP_LOGIN_USERNAME = 'forminputgroup-log';
    public final const COMPONENT_FORMINPUTGROUP_LOGIN_PWD = 'forminputgroup-pwd';
    public final const COMPONENT_FORMINPUTGROUP_LOSTPWD_USERNAME = 'forminputgroup-lostpwd-username';
    public final const COMPONENT_FORMINPUTGROUP_LOSTPWDRESET_CODE = 'forminputgroup-lostpwdreset-code';
    public final const COMPONENT_FORMINPUTGROUP_LOSTPWDRESET_NEWPASSWORD = 'forminputgroup-lostpwdreset-newpassword';
    public final const COMPONENT_FORMINPUTGROUP_LOSTPWDRESET_PASSWORDREPEAT = 'forminputgroup-lostpwdreset-passwordrepeat';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMINPUTGROUP_LOGIN_USERNAME],
            [self::class, self::COMPONENT_FORMINPUTGROUP_LOGIN_PWD],
            [self::class, self::COMPONENT_FORMINPUTGROUP_LOSTPWD_USERNAME],
            [self::class, self::COMPONENT_FORMINPUTGROUP_LOSTPWDRESET_CODE],
            [self::class, self::COMPONENT_FORMINPUTGROUP_LOSTPWDRESET_NEWPASSWORD],
            [self::class, self::COMPONENT_FORMINPUTGROUP_LOSTPWDRESET_PASSWORDREPEAT],
        );
    }

    public function getComponentSubmodule(array $component)
    {
        $components = array(
            self::COMPONENT_FORMINPUTGROUP_LOGIN_USERNAME => [PoP_Module_Processor_LoginTextFormInputs::class, PoP_Module_Processor_LoginTextFormInputs::COMPONENT_FORMINPUT_LOGIN_USERNAME],
            self::COMPONENT_FORMINPUTGROUP_LOGIN_PWD => [PoP_Module_Processor_LoginTextFormInputs::class, PoP_Module_Processor_LoginTextFormInputs::COMPONENT_FORMINPUT_LOGIN_PWD],
            self::COMPONENT_FORMINPUTGROUP_LOSTPWD_USERNAME => [PoP_Module_Processor_LoginTextFormInputs::class, PoP_Module_Processor_LoginTextFormInputs::COMPONENT_FORMINPUT_LOSTPWD_USERNAME],
            self::COMPONENT_FORMINPUTGROUP_LOSTPWDRESET_CODE => [PoP_Module_Processor_LoginTextFormInputs::class, PoP_Module_Processor_LoginTextFormInputs::COMPONENT_FORMINPUT_LOSTPWDRESET_CODE],
            self::COMPONENT_FORMINPUTGROUP_LOSTPWDRESET_NEWPASSWORD => [PoP_Module_Processor_LoginTextFormInputs::class, PoP_Module_Processor_LoginTextFormInputs::COMPONENT_FORMINPUT_LOSTPWDRESET_NEWPASSWORD],
            self::COMPONENT_FORMINPUTGROUP_LOSTPWDRESET_PASSWORDREPEAT => [PoP_Module_Processor_LoginTextFormInputs::class, PoP_Module_Processor_LoginTextFormInputs::COMPONENT_FORMINPUT_LOSTPWDRESET_PASSWORDREPEAT],
        );

        if ($component = $components[$component[1]] ?? null) {
            return $component;
        }

        return parent::getComponentSubmodule($component);
    }
}



