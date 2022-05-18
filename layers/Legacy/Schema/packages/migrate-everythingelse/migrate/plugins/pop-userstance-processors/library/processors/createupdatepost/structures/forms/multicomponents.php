<?php

class UserStance_Module_Processor_FormMultipleComponents extends PoP_Module_Processor_MultiplesBase
{
    public final const MODULE_MULTICOMPONENT_FORM_STANCE_MAYBELEFTSIDE = 'multicomponent-form-stance-maybeleftside';
    public final const MODULE_MULTICOMPONENT_FORM_STANCE_MAYBERIGHTSIDE = 'multicomponent-form-stance-mayberightside';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_MULTICOMPONENT_FORM_STANCE_MAYBELEFTSIDE],
            [self::class, self::MODULE_MULTICOMPONENT_FORM_STANCE_MAYBERIGHTSIDE],
        );
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);

        switch ($componentVariation[1]) {
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



