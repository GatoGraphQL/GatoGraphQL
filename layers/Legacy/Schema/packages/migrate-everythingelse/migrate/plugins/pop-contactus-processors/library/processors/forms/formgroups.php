<?php

class PoP_ContactUs_Module_Processor_FormGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public final const COMPONENT_FORMINPUTGROUP_TOPIC = 'gf-forminputgroup-field-topic';
    public final const COMPONENT_FORMINPUTGROUP_SUBJECT = 'gf-forminputgroup-field-subject';
    public final const COMPONENT_FORMINPUTGROUP_MESSAGE = 'gf-forminputgroup-field-message';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMINPUTGROUP_TOPIC],
            [self::class, self::COMPONENT_FORMINPUTGROUP_SUBJECT],
            [self::class, self::COMPONENT_FORMINPUTGROUP_MESSAGE],
        );
    }

    public function getComponentSubmodule(array $component)
    {
        $components = array(
            self::COMPONENT_FORMINPUTGROUP_TOPIC => [GenericForms_Module_Processor_SelectFormInputs::class, GenericForms_Module_Processor_SelectFormInputs::COMPONENT_FORMINPUT_TOPIC],
            self::COMPONENT_FORMINPUTGROUP_SUBJECT => [PoP_ContactUs_Module_Processor_TextFormInputs::class, PoP_ContactUs_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_SUBJECT],
            self::COMPONENT_FORMINPUTGROUP_MESSAGE => [PoP_ContactUs_Module_Processor_TextareaFormInputs::class, PoP_ContactUs_Module_Processor_TextareaFormInputs::COMPONENT_FORMINPUT_MESSAGE],
        );

        if ($component = $components[$component[1]] ?? null) {
            return $component;
        }

        return parent::getComponentSubmodule($component);
    }
}



