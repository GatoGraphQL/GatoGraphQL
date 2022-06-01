<?php

class PoP_Module_Processor_FeedButtonWrappers extends PoP_Module_Processor_ShowIfNotEmptyConditionWrapperBase
{
    public final const COMPONENT_BUTTONWRAPPER_TOGGLEUSERPOSTACTIVITY = 'buttonwrapper-userpostactivity';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BUTTONWRAPPER_TOGGLEUSERPOSTACTIVITY],
        );
    }

    public function getConditionSucceededSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getConditionSucceededSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_BUTTONWRAPPER_TOGGLEUSERPOSTACTIVITY:
                $ret[] = [PoP_Module_Processor_FeedButtons::class, PoP_Module_Processor_FeedButtons::COMPONENT_BUTTON_TOGGLEUSERPOSTACTIVITY];
                break;
        }

        return $ret;
    }

    public function getConditionField(\PoP\ComponentModel\Component\Component $component): ?string
    {
        switch ($component->name) {
            case self::COMPONENT_BUTTONWRAPPER_TOGGLEUSERPOSTACTIVITY:
                return 'hasUserpostactivity';
        }

        return null;
    }

    public function getTextfieldComponent(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_BUTTONWRAPPER_TOGGLEUSERPOSTACTIVITY:
                return [PoP_Module_Processor_FeedButtonInners::class, PoP_Module_Processor_FeedButtonInners::COMPONENT_BUTTONINNER_TOGGLEUSERPOSTACTIVITY];
        }

        return parent::getTextfieldComponent($component, $props);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_BUTTONWRAPPER_TOGGLEUSERPOSTACTIVITY:
                $this->appendProp($component, $props, 'class', 'pop-collapse-btn');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



