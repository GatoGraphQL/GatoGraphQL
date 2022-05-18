<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class UserStance_Module_Processor_CreateUpdatePostFormInputGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public final const MODULE_FORMINPUTGROUP_STANCEEDITOR = 'forminput-stanceeditorgroup';
    public final const MODULE_FORMINPUTGROUP_BUTTONGROUP_STANCE = 'forminputgroup-buttongroup-stance';
    public final const MODULE_FILTERINPUTGROUP_BUTTONGROUP_STANCE = 'filterinputgroup-buttongroup-stance';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUTGROUP_STANCEEDITOR],
            [self::class, self::MODULE_FORMINPUTGROUP_BUTTONGROUP_STANCE],
            [self::class, self::MODULE_FILTERINPUTGROUP_BUTTONGROUP_STANCE],
        );
    }

    public function getComponentSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUTGROUP_STANCEEDITOR:
                return [PoP_Module_Processor_TextareaFormInputs::class, PoP_Module_Processor_TextareaFormInputs::MODULE_FORMINPUT_TEXTAREAEDITOR];

            case self::MODULE_FORMINPUTGROUP_BUTTONGROUP_STANCE:
                return [UserStance_Module_Processor_ButtonGroupFormInputs::class, UserStance_Module_Processor_ButtonGroupFormInputs::MODULE_FORMINPUT_BUTTONGROUP_STANCE];

            case self::MODULE_FILTERINPUTGROUP_BUTTONGROUP_STANCE:
                return [UserStance_Module_Processor_MultiSelectFilterInputs::class, UserStance_Module_Processor_MultiSelectFilterInputs::MODULE_FILTERINPUT_STANCE_MULTISELECT];
        }

        return parent::getComponentSubmodule($componentVariation);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUTGROUP_STANCEEDITOR:
                $component = $this->getComponentSubmodule($componentVariation);
                $this->setProp($component, $props, 'placeholder', TranslationAPIFacade::getInstance()->__('Write here...', 'pop-userstance-processors'));
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }

    public function getInfo(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUTGROUP_STANCEEDITOR:
                return TranslationAPIFacade::getInstance()->__('You can leave 1 general stance, and 1 stance for each article on the website. Your opinions can be edited any moment.', 'pop-userstance-processors');
        }

        return parent::getInfo($componentVariation, $props);
    }

    public function getLabel(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUTGROUP_STANCEEDITOR:
                return PoP_UserStanceProcessors_Utils::getWhatisyourvoteTitle();

            case self::MODULE_FORMINPUTGROUP_BUTTONGROUP_STANCE:
                return TranslationAPIFacade::getInstance()->__('Your stance:', 'pop-userstance-processors');

            case self::MODULE_FILTERINPUTGROUP_BUTTONGROUP_STANCE:
                return TranslationAPIFacade::getInstance()->__('Stance:', 'pop-userstance-processors');
        }

        return parent::getLabel($componentVariation, $props);
    }

    public function getLabelClass(array $componentVariation)
    {
        $ret = parent::getLabelClass($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_FILTERINPUTGROUP_BUTTONGROUP_STANCE:
                $ret .= ' col-sm-2';
                break;
        }

        return $ret;
    }
    public function getFormcontrolClass(array $componentVariation)
    {
        $ret = parent::getFormcontrolClass($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_FILTERINPUTGROUP_BUTTONGROUP_STANCE:
                $ret .= ' col-sm-10';
                break;
        }

        return $ret;
    }
}



