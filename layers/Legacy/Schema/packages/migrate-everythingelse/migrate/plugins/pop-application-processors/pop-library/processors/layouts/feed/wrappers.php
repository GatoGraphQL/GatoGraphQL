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

    public function getConditionSucceededSubcomponents(array $component)
    {
        $ret = parent::getConditionSucceededSubcomponents($component);

        switch ($component[1]) {
            case self::COMPONENT_BUTTONWRAPPER_TOGGLEUSERPOSTACTIVITY:
                $ret[] = [PoP_Module_Processor_FeedButtons::class, PoP_Module_Processor_FeedButtons::COMPONENT_BUTTON_TOGGLEUSERPOSTACTIVITY];
                break;
        }

        return $ret;
    }

    public function getConditionField(array $component): ?string
    {
        switch ($component[1]) {
            case self::COMPONENT_BUTTONWRAPPER_TOGGLEUSERPOSTACTIVITY:
                return 'hasUserpostactivity';
        }

        return null;
    }

    public function getTextfieldModule(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_BUTTONWRAPPER_TOGGLEUSERPOSTACTIVITY:
                return [PoP_Module_Processor_FeedButtonInners::class, PoP_Module_Processor_FeedButtonInners::COMPONENT_BUTTONINNER_TOGGLEUSERPOSTACTIVITY];
        }

        return parent::getTextfieldModule($component, $props);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_BUTTONWRAPPER_TOGGLEUSERPOSTACTIVITY:
                $this->appendProp($component, $props, 'class', 'pop-collapse-btn');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



