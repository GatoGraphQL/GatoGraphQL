<?php

class UserStance_Module_Processor_FormMultipleComponents extends PoP_Module_Processor_MultiplesBase
{
    public const MODULE_MULTICOMPONENT_FORM_STANCE_MAYBELEFTSIDE = 'multicomponent-form-stance-maybeleftside';
    public const MODULE_MULTICOMPONENT_FORM_STANCE_MAYBERIGHTSIDE = 'multicomponent-form-stance-mayberightside';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_MULTICOMPONENT_FORM_STANCE_MAYBELEFTSIDE],
            [self::class, self::MODULE_MULTICOMPONENT_FORM_STANCE_MAYBERIGHTSIDE],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_MULTICOMPONENT_FORM_STANCE_MAYBELEFTSIDE:
                $ret[] = [UserStance_Module_Processor_FormComponentGroupsGroups::class, UserStance_Module_Processor_FormComponentGroupsGroups::MODULE_FORMCOMPONENTGROUP_CARD_STANCETARGET];
                $ret[] = [UserStance_Module_Processor_CreateUpdatePostFormInputGroups::class, UserStance_Module_Processor_CreateUpdatePostFormInputGroups::MODULE_FORMINPUTGROUP_STANCEEDITOR];
                break;

            case self::MODULE_MULTICOMPONENT_FORM_STANCE_MAYBERIGHTSIDE:
                $ret[] = [UserStance_Module_Processor_CreateUpdatePostFormInputGroups::class, UserStance_Module_Processor_CreateUpdatePostFormInputGroups::MODULE_FORMINPUTGROUP_BUTTONGROUP_STANCE];
                $ret[] = [Wassup_Module_Processor_FormMultipleComponents::class, Wassup_Module_Processor_FormMultipleComponents::MODULE_MULTICOMPONENT_FORMINPUTS_UNMODERATEDPUBLISH];
                break;
        }

        return $ret;
    }
}



