<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class UserStance_Module_Processor_CreateUpdatePostFormInputGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public final const COMPONENT_FORMINPUTGROUP_STANCEEDITOR = 'forminput-stanceeditorgroup';
    public final const COMPONENT_FORMINPUTGROUP_BUTTONGROUP_STANCE = 'forminputgroup-buttongroup-stance';
    public final const COMPONENT_FILTERINPUTGROUP_BUTTONGROUP_STANCE = 'filterinputgroup-buttongroup-stance';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMINPUTGROUP_STANCEEDITOR],
            [self::class, self::COMPONENT_FORMINPUTGROUP_BUTTONGROUP_STANCE],
            [self::class, self::COMPONENT_FILTERINPUTGROUP_BUTTONGROUP_STANCE],
        );
    }

    public function getComponentSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUTGROUP_STANCEEDITOR:
                return [PoP_Module_Processor_TextareaFormInputs::class, PoP_Module_Processor_TextareaFormInputs::COMPONENT_FORMINPUT_TEXTAREAEDITOR];

            case self::COMPONENT_FORMINPUTGROUP_BUTTONGROUP_STANCE:
                return [UserStance_Module_Processor_ButtonGroupFormInputs::class, UserStance_Module_Processor_ButtonGroupFormInputs::COMPONENT_FORMINPUT_BUTTONGROUP_STANCE];

            case self::COMPONENT_FILTERINPUTGROUP_BUTTONGROUP_STANCE:
                return [UserStance_Module_Processor_MultiSelectFilterInputs::class, UserStance_Module_Processor_MultiSelectFilterInputs::COMPONENT_FILTERINPUT_STANCE_MULTISELECT];
        }

        return parent::getComponentSubmodule($component);
    }

    public function initModelProps(array $component, array &$props): void
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        switch ($component[1]) {
            case self::COMPONENT_FORMINPUTGROUP_STANCEEDITOR:
                $component = $this->getComponentSubmodule($component);
                $this->setProp($component, $props, 'placeholder', TranslationAPIFacade::getInstance()->__('Write here...', 'pop-userstance-processors'));
                break;
        }

        parent::initModelProps($component, $props);
    }

    public function getInfo(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUTGROUP_STANCEEDITOR:
                return TranslationAPIFacade::getInstance()->__('You can leave 1 general stance, and 1 stance for each article on the website. Your opinions can be edited any moment.', 'pop-userstance-processors');
        }

        return parent::getInfo($component, $props);
    }

    public function getLabel(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUTGROUP_STANCEEDITOR:
                return PoP_UserStanceProcessors_Utils::getWhatisyourvoteTitle();

            case self::COMPONENT_FORMINPUTGROUP_BUTTONGROUP_STANCE:
                return TranslationAPIFacade::getInstance()->__('Your stance:', 'pop-userstance-processors');

            case self::COMPONENT_FILTERINPUTGROUP_BUTTONGROUP_STANCE:
                return TranslationAPIFacade::getInstance()->__('Stance:', 'pop-userstance-processors');
        }

        return parent::getLabel($component, $props);
    }

    public function getLabelClass(array $component)
    {
        $ret = parent::getLabelClass($component);

        switch ($component[1]) {
            case self::COMPONENT_FILTERINPUTGROUP_BUTTONGROUP_STANCE:
                $ret .= ' col-sm-2';
                break;
        }

        return $ret;
    }
    public function getFormcontrolClass(array $component)
    {
        $ret = parent::getFormcontrolClass($component);

        switch ($component[1]) {
            case self::COMPONENT_FILTERINPUTGROUP_BUTTONGROUP_STANCE:
                $ret .= ' col-sm-10';
                break;
        }

        return $ret;
    }
}



