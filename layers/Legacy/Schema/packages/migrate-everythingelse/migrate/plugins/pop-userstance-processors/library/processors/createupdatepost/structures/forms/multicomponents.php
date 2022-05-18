<?php

class UserStance_Module_Processor_FormMultipleComponents extends PoP_Module_Processor_MultiplesBase
{
    public final const MODULE_MULTICOMPONENT_FORM_STANCE_MAYBELEFTSIDE = 'multicomponent-form-stance-maybeleftside';
    public final const MODULE_MULTICOMPONENT_FORM_STANCE_MAYBERIGHTSIDE = 'multicomponent-form-stance-mayberightside';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_MULTICOMPONENT_FORM_STANCE_MAYBELEFTSIDE],
            [self::class, self::COMPONENT_MULTICOMPONENT_FORM_STANCE_MAYBERIGHTSIDE],
        );
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);

        switch ($component[1]) {
            case self::COMPONENT_MULTICOMPONENT_FORM_STANCE_MAYBELEFTSIDE:
                $ret[] = [UserStance_Module_Processor_FormComponentGroupsGroups::class, UserStance_Module_Processor_FormComponentGroupsGroups::COMPONENT_FORMCOMPONENTGROUP_CARD_STANCETARGET];
                $ret[] = [UserStance_Module_Processor_CreateUpdatePostFormInputGroups::class, UserStance_Module_Processor_CreateUpdatePostFormInputGroups::COMPONENT_FORMINPUTGROUP_STANCEEDITOR];
                break;

            case self::COMPONENT_MULTICOMPONENT_FORM_STANCE_MAYBERIGHTSIDE:
                $ret[] = [UserStance_Module_Processor_CreateUpdatePostFormInputGroups::class, UserStance_Module_Processor_CreateUpdatePostFormInputGroups::COMPONENT_FORMINPUTGROUP_BUTTONGROUP_STANCE];
                $ret[] = [Wassup_Module_Processor_FormMultipleComponents::class, Wassup_Module_Processor_FormMultipleComponents::COMPONENT_MULTICOMPONENT_FORMINPUTS_UNMODERATEDPUBLISH];
                break;
        }

        return $ret;
    }
}



