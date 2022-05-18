<?php

class GD_CommonUserRoles_Module_Processor_ProfileFormGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public final const COMPONENT_URE_FORMINPUTGROUP_CUP_CONTACTPERSON = 'forminputgroup-ure-cup-contactperson';
    public final const COMPONENT_URE_FORMINPUTGROUP_CUP_CONTACTNUMBER = 'forminputgroup-ure-cup-contactnumber';
    public final const COMPONENT_URE_FORMINPUTGROUP_CUP_LASTNAME = 'forminputgroup-ure-cup-lastName';
    public final const COMPONENT_URE_FORMINPUTGROUP_INDIVIDUALINTERESTS = 'ure-forminputgroup-individualinterests';
    public final const COMPONENT_URE_FORMINPUTGROUP_ORGANIZATIONCATEGORIES = 'ure-forminputgroup-organizationcategories';
    public final const COMPONENT_URE_FORMINPUTGROUP_ORGANIZATIONTYPES = 'ure-forminputgroup-organizationtypes';
    public final const COMPONENT_URE_FILTERINPUTGROUP_INDIVIDUALINTERESTS = 'filterinputgroup-individualinterests';
    public final const COMPONENT_URE_FILTERINPUTGROUP_ORGANIZATIONCATEGORIES = 'filterinputgroup-organizationcategories';
    public final const COMPONENT_URE_FILTERINPUTGROUP_ORGANIZATIONTYPES = 'filterinputgroup-organizationtypes';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_URE_FORMINPUTGROUP_CUP_CONTACTPERSON],
            [self::class, self::COMPONENT_URE_FORMINPUTGROUP_CUP_CONTACTNUMBER],
            [self::class, self::COMPONENT_URE_FORMINPUTGROUP_CUP_LASTNAME],
            [self::class, self::COMPONENT_URE_FORMINPUTGROUP_INDIVIDUALINTERESTS],
            [self::class, self::COMPONENT_URE_FORMINPUTGROUP_ORGANIZATIONCATEGORIES],
            [self::class, self::COMPONENT_URE_FORMINPUTGROUP_ORGANIZATIONTYPES],
            [self::class, self::COMPONENT_URE_FILTERINPUTGROUP_INDIVIDUALINTERESTS],
            [self::class, self::COMPONENT_URE_FILTERINPUTGROUP_ORGANIZATIONCATEGORIES],
            [self::class, self::COMPONENT_URE_FILTERINPUTGROUP_ORGANIZATIONTYPES],
        );
    }

    public function getLabelClass(array $component)
    {
        $ret = parent::getLabelClass($component);

        switch ($component[1]) {
            case self::COMPONENT_URE_FILTERINPUTGROUP_INDIVIDUALINTERESTS:
            case self::COMPONENT_URE_FILTERINPUTGROUP_ORGANIZATIONCATEGORIES:
            case self::COMPONENT_URE_FILTERINPUTGROUP_ORGANIZATIONTYPES:
                $ret .= ' col-sm-2';
                break;
        }

        return $ret;
    }
    public function getFormcontrolClass(array $component)
    {
        $ret = parent::getFormcontrolClass($component);

        switch ($component[1]) {
            case self::COMPONENT_URE_FILTERINPUTGROUP_INDIVIDUALINTERESTS:
            case self::COMPONENT_URE_FILTERINPUTGROUP_ORGANIZATIONCATEGORIES:
            case self::COMPONENT_URE_FILTERINPUTGROUP_ORGANIZATIONTYPES:
                $ret .= ' col-sm-10';
                break;
        }

        return $ret;
    }

    public function getComponentSubmodule(array $component)
    {
        $components = array(
            self::COMPONENT_URE_FORMINPUTGROUP_CUP_CONTACTPERSON => [GD_URE_Module_Processor_TextFormInputs::class, GD_URE_Module_Processor_TextFormInputs::COMPONENT_URE_FORMINPUT_CUP_CONTACTPERSON],
            self::COMPONENT_URE_FORMINPUTGROUP_CUP_CONTACTNUMBER => [GD_URE_Module_Processor_TextFormInputs::class, GD_URE_Module_Processor_TextFormInputs::COMPONENT_URE_FORMINPUT_CUP_CONTACTNUMBER],
            self::COMPONENT_URE_FORMINPUTGROUP_CUP_LASTNAME => [GD_URE_Module_Processor_TextFormInputs::class, GD_URE_Module_Processor_TextFormInputs::COMPONENT_URE_FORMINPUT_CUP_LASTNAME],
            self::COMPONENT_URE_FORMINPUTGROUP_INDIVIDUALINTERESTS => [GD_URE_Module_Processor_MultiSelectFormInputs::class, GD_URE_Module_Processor_MultiSelectFormInputs::COMPONENT_URE_FORMINPUT_INDIVIDUALINTERESTS],
            self::COMPONENT_URE_FORMINPUTGROUP_ORGANIZATIONCATEGORIES => [GD_URE_Module_Processor_MultiSelectFormInputs::class, GD_URE_Module_Processor_MultiSelectFormInputs::COMPONENT_URE_FORMINPUT_ORGANIZATIONCATEGORIES],
            self::COMPONENT_URE_FORMINPUTGROUP_ORGANIZATIONTYPES => [GD_URE_Module_Processor_MultiSelectFormInputs::class, GD_URE_Module_Processor_MultiSelectFormInputs::COMPONENT_URE_FORMINPUT_ORGANIZATIONTYPES],
            self::COMPONENT_URE_FILTERINPUTGROUP_INDIVIDUALINTERESTS => [GD_URE_Module_Processor_MultiSelectFilterInputs::class, GD_URE_Module_Processor_MultiSelectFilterInputs::COMPONENT_URE_FILTERINPUT_INDIVIDUALINTERESTS],
            self::COMPONENT_URE_FILTERINPUTGROUP_ORGANIZATIONCATEGORIES => [GD_URE_Module_Processor_MultiSelectFilterInputs::class, GD_URE_Module_Processor_MultiSelectFilterInputs::COMPONENT_URE_FILTERINPUT_ORGANIZATIONCATEGORIES],
            self::COMPONENT_URE_FILTERINPUTGROUP_ORGANIZATIONTYPES => [GD_URE_Module_Processor_MultiSelectFilterInputs::class, GD_URE_Module_Processor_MultiSelectFilterInputs::COMPONENT_URE_FILTERINPUT_ORGANIZATIONTYPES],
        );

        if ($component = $components[$component[1]] ?? null) {
            return $component;
        }

        return parent::getComponentSubmodule($component);
    }
}



