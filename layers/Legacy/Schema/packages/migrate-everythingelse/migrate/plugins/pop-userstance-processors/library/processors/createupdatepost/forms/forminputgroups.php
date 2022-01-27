<?php
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class UserStance_Module_Processor_CreateUpdatePostFormInputGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public const MODULE_FORMINPUTGROUP_STANCEEDITOR = 'forminput-stanceeditorgroup';
    public const MODULE_FORMINPUTGROUP_BUTTONGROUP_STANCE = 'forminputgroup-buttongroup-stance';
    public const MODULE_FILTERINPUTGROUP_BUTTONGROUP_STANCE = 'filterinputgroup-buttongroup-stance';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUTGROUP_STANCEEDITOR],
            [self::class, self::MODULE_FORMINPUTGROUP_BUTTONGROUP_STANCE],
            [self::class, self::MODULE_FILTERINPUTGROUP_BUTTONGROUP_STANCE],
        );
    }

    public function getComponentSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUTGROUP_STANCEEDITOR:
                return [PoP_Module_Processor_TextareaFormInputs::class, PoP_Module_Processor_TextareaFormInputs::MODULE_FORMINPUT_TEXTAREAEDITOR];

            case self::MODULE_FORMINPUTGROUP_BUTTONGROUP_STANCE:
                return [UserStance_Module_Processor_ButtonGroupFormInputs::class, UserStance_Module_Processor_ButtonGroupFormInputs::MODULE_FORMINPUT_BUTTONGROUP_STANCE];

            case self::MODULE_FILTERINPUTGROUP_BUTTONGROUP_STANCE:
                return [UserStance_Module_Processor_MultiSelectFilterInputs::class, UserStance_Module_Processor_MultiSelectFilterInputs::MODULE_FILTERINPUT_STANCE_MULTISELECT];
        }

        return parent::getComponentSubmodule($module);
    }

    public function initModelProps(array $module, array &$props): void
    {
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        switch ($module[1]) {
            case self::MODULE_FORMINPUTGROUP_STANCEEDITOR:
                $component = $this->getComponentSubmodule($module);
                $this->setProp($component, $props, 'placeholder', TranslationAPIFacade::getInstance()->__('Write here...', 'pop-userstance-processors'));
                break;
        }

        parent::initModelProps($module, $props);
    }

    public function getInfo(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUTGROUP_STANCEEDITOR:
                return TranslationAPIFacade::getInstance()->__('You can leave 1 general stance, and 1 stance for each article on the website. Your opinions can be edited any moment.', 'pop-userstance-processors');
        }

        return parent::getInfo($module, $props);
    }

    public function getLabel(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUTGROUP_STANCEEDITOR:
                return PoP_UserStanceProcessors_Utils::getWhatisyourvoteTitle();

            case self::MODULE_FORMINPUTGROUP_BUTTONGROUP_STANCE:
                return TranslationAPIFacade::getInstance()->__('Your stance:', 'pop-userstance-processors');

            case self::MODULE_FILTERINPUTGROUP_BUTTONGROUP_STANCE:
                return TranslationAPIFacade::getInstance()->__('Stance:', 'pop-userstance-processors');
        }

        return parent::getLabel($module, $props);
    }

    public function getLabelClass(array $module)
    {
        $ret = parent::getLabelClass($module);

        switch ($module[1]) {
            case self::MODULE_FILTERINPUTGROUP_BUTTONGROUP_STANCE:
                $ret .= ' col-sm-2';
                break;
        }

        return $ret;
    }
    public function getFormcontrolClass(array $module)
    {
        $ret = parent::getFormcontrolClass($module);

        switch ($module[1]) {
            case self::MODULE_FILTERINPUTGROUP_BUTTONGROUP_STANCE:
                $ret .= ' col-sm-10';
                break;
        }

        return $ret;
    }
}



