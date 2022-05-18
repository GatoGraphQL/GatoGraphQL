<?php

class PoP_Newsletter_Module_Processor_FormGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public final const MODULE_FORMINPUTGROUP_NEWSLETTERNAME = 'forminputgroup-field-newslettername';
    public final const MODULE_FORMINPUTGROUP_NEWSLETTEREMAIL = 'forminputgroup-field-newsletteremail';
    public final const MODULE_FORMINPUTGROUP_NEWSLETTEREMAILVERIFICATIONEMAIL = 'forminputgroup-field-newsletteremailverificationemail';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUTGROUP_NEWSLETTERNAME],
            [self::class, self::MODULE_FORMINPUTGROUP_NEWSLETTEREMAIL],
            [self::class, self::MODULE_FORMINPUTGROUP_NEWSLETTEREMAILVERIFICATIONEMAIL],
        );
    }

    public function getComponentSubmodule(array $module)
    {
        $components = array(
            self::MODULE_FORMINPUTGROUP_NEWSLETTERNAME => [PoP_Newsletter_Module_Processor_TextFormInputs::class, PoP_Newsletter_Module_Processor_TextFormInputs::MODULE_FORMINPUT_NEWSLETTERNAME],
            self::MODULE_FORMINPUTGROUP_NEWSLETTEREMAIL => [PoP_Newsletter_Module_Processor_TextFormInputs::class, PoP_Newsletter_Module_Processor_TextFormInputs::MODULE_FORMINPUT_NEWSLETTEREMAIL],
            self::MODULE_FORMINPUTGROUP_NEWSLETTEREMAILVERIFICATIONEMAIL => [PoP_Newsletter_Module_Processor_TextFormInputs::class, PoP_Newsletter_Module_Processor_TextFormInputs::MODULE_FORMINPUT_NEWSLETTEREMAILVERIFICATIONEMAIL],
        );

        if ($component = $components[$module[1]] ?? null) {
            return $component;
        }

        return parent::getComponentSubmodule($module);
    }
}



