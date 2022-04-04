<?php

class GD_CommonUserRoles_Module_Processor_ProfileFormGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public final const MODULE_URE_FORMINPUTGROUP_CUP_CONTACTPERSON = 'forminputgroup-ure-cup-contactperson';
    public final const MODULE_URE_FORMINPUTGROUP_CUP_CONTACTNUMBER = 'forminputgroup-ure-cup-contactnumber';
    public final const MODULE_URE_FORMINPUTGROUP_CUP_LASTNAME = 'forminputgroup-ure-cup-lastName';
    public final const MODULE_URE_FORMINPUTGROUP_INDIVIDUALINTERESTS = 'ure-forminputgroup-individualinterests';
    public final const MODULE_URE_FORMINPUTGROUP_ORGANIZATIONCATEGORIES = 'ure-forminputgroup-organizationcategories';
    public final const MODULE_URE_FORMINPUTGROUP_ORGANIZATIONTYPES = 'ure-forminputgroup-organizationtypes';
    public final const MODULE_URE_FILTERINPUTGROUP_INDIVIDUALINTERESTS = 'filterinputgroup-individualinterests';
    public final const MODULE_URE_FILTERINPUTGROUP_ORGANIZATIONCATEGORIES = 'filterinputgroup-organizationcategories';
    public final const MODULE_URE_FILTERINPUTGROUP_ORGANIZATIONTYPES = 'filterinputgroup-organizationtypes';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_URE_FORMINPUTGROUP_CUP_CONTACTPERSON],
            [self::class, self::MODULE_URE_FORMINPUTGROUP_CUP_CONTACTNUMBER],
            [self::class, self::MODULE_URE_FORMINPUTGROUP_CUP_LASTNAME],
            [self::class, self::MODULE_URE_FORMINPUTGROUP_INDIVIDUALINTERESTS],
            [self::class, self::MODULE_URE_FORMINPUTGROUP_ORGANIZATIONCATEGORIES],
            [self::class, self::MODULE_URE_FORMINPUTGROUP_ORGANIZATIONTYPES],
            [self::class, self::MODULE_URE_FILTERINPUTGROUP_INDIVIDUALINTERESTS],
            [self::class, self::MODULE_URE_FILTERINPUTGROUP_ORGANIZATIONCATEGORIES],
            [self::class, self::MODULE_URE_FILTERINPUTGROUP_ORGANIZATIONTYPES],
        );
    }

    public function getLabelClass(array $module)
    {
        $ret = parent::getLabelClass($module);

        switch ($module[1]) {
            case self::MODULE_URE_FILTERINPUTGROUP_INDIVIDUALINTERESTS:
            case self::MODULE_URE_FILTERINPUTGROUP_ORGANIZATIONCATEGORIES:
            case self::MODULE_URE_FILTERINPUTGROUP_ORGANIZATIONTYPES:
                $ret .= ' col-sm-2';
                break;
        }

        return $ret;
    }
    public function getFormcontrolClass(array $module)
    {
        $ret = parent::getFormcontrolClass($module);

        switch ($module[1]) {
            case self::MODULE_URE_FILTERINPUTGROUP_INDIVIDUALINTERESTS:
            case self::MODULE_URE_FILTERINPUTGROUP_ORGANIZATIONCATEGORIES:
            case self::MODULE_URE_FILTERINPUTGROUP_ORGANIZATIONTYPES:
                $ret .= ' col-sm-10';
                break;
        }

        return $ret;
    }

    public function getComponentSubmodule(array $module)
    {
        $components = array(
            self::MODULE_URE_FORMINPUTGROUP_CUP_CONTACTPERSON => [GD_URE_Module_Processor_TextFormInputs::class, GD_URE_Module_Processor_TextFormInputs::MODULE_URE_FORMINPUT_CUP_CONTACTPERSON],
            self::MODULE_URE_FORMINPUTGROUP_CUP_CONTACTNUMBER => [GD_URE_Module_Processor_TextFormInputs::class, GD_URE_Module_Processor_TextFormInputs::MODULE_URE_FORMINPUT_CUP_CONTACTNUMBER],
            self::MODULE_URE_FORMINPUTGROUP_CUP_LASTNAME => [GD_URE_Module_Processor_TextFormInputs::class, GD_URE_Module_Processor_TextFormInputs::MODULE_URE_FORMINPUT_CUP_LASTNAME],
            self::MODULE_URE_FORMINPUTGROUP_INDIVIDUALINTERESTS => [GD_URE_Module_Processor_MultiSelectFormInputs::class, GD_URE_Module_Processor_MultiSelectFormInputs::MODULE_URE_FORMINPUT_INDIVIDUALINTERESTS],
            self::MODULE_URE_FORMINPUTGROUP_ORGANIZATIONCATEGORIES => [GD_URE_Module_Processor_MultiSelectFormInputs::class, GD_URE_Module_Processor_MultiSelectFormInputs::MODULE_URE_FORMINPUT_ORGANIZATIONCATEGORIES],
            self::MODULE_URE_FORMINPUTGROUP_ORGANIZATIONTYPES => [GD_URE_Module_Processor_MultiSelectFormInputs::class, GD_URE_Module_Processor_MultiSelectFormInputs::MODULE_URE_FORMINPUT_ORGANIZATIONTYPES],
            self::MODULE_URE_FILTERINPUTGROUP_INDIVIDUALINTERESTS => [GD_URE_Module_Processor_MultiSelectFilterInputs::class, GD_URE_Module_Processor_MultiSelectFilterInputs::MODULE_URE_FILTERINPUT_INDIVIDUALINTERESTS],
            self::MODULE_URE_FILTERINPUTGROUP_ORGANIZATIONCATEGORIES => [GD_URE_Module_Processor_MultiSelectFilterInputs::class, GD_URE_Module_Processor_MultiSelectFilterInputs::MODULE_URE_FILTERINPUT_ORGANIZATIONCATEGORIES],
            self::MODULE_URE_FILTERINPUTGROUP_ORGANIZATIONTYPES => [GD_URE_Module_Processor_MultiSelectFilterInputs::class, GD_URE_Module_Processor_MultiSelectFilterInputs::MODULE_URE_FILTERINPUT_ORGANIZATIONTYPES],
        );

        if ($component = $components[$module[1]] ?? null) {
            return $component;
        }

        return parent::getComponentSubmodule($module);
    }
}



