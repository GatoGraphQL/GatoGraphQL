<?php

class PoP_ContactUs_Module_Processor_FormGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public const MODULE_FORMINPUTGROUP_TOPIC = 'gf-forminputgroup-field-topic';
    public const MODULE_FORMINPUTGROUP_SUBJECT = 'gf-forminputgroup-field-subject';
    public const MODULE_FORMINPUTGROUP_MESSAGE = 'gf-forminputgroup-field-message';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUTGROUP_TOPIC],
            [self::class, self::MODULE_FORMINPUTGROUP_SUBJECT],
            [self::class, self::MODULE_FORMINPUTGROUP_MESSAGE],
        );
    }

    public function getComponentSubmodule(array $module)
    {
        $components = array(
            self::MODULE_FORMINPUTGROUP_TOPIC => [GenericForms_Module_Processor_SelectFormInputs::class, GenericForms_Module_Processor_SelectFormInputs::MODULE_FORMINPUT_TOPIC],
            self::MODULE_FORMINPUTGROUP_SUBJECT => [PoP_ContactUs_Module_Processor_TextFormInputs::class, PoP_ContactUs_Module_Processor_TextFormInputs::MODULE_FORMINPUT_SUBJECT],
            self::MODULE_FORMINPUTGROUP_MESSAGE => [PoP_ContactUs_Module_Processor_TextareaFormInputs::class, PoP_ContactUs_Module_Processor_TextareaFormInputs::MODULE_FORMINPUT_MESSAGE],
        );

        if ($component = $components[$module[1]] ?? null) {
            return $component;
        }

        return parent::getComponentSubmodule($module);
    }
}



