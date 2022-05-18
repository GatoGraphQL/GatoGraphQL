<?php

class PoP_Module_Processor_FeedButtonWrappers extends PoP_Module_Processor_ShowIfNotEmptyConditionWrapperBase
{
    public final const MODULE_BUTTONWRAPPER_TOGGLEUSERPOSTACTIVITY = 'buttonwrapper-userpostactivity';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BUTTONWRAPPER_TOGGLEUSERPOSTACTIVITY],
        );
    }

    public function getConditionSucceededSubmodules(array $componentVariation)
    {
        $ret = parent::getConditionSucceededSubmodules($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_BUTTONWRAPPER_TOGGLEUSERPOSTACTIVITY:
                $ret[] = [PoP_Module_Processor_FeedButtons::class, PoP_Module_Processor_FeedButtons::MODULE_BUTTON_TOGGLEUSERPOSTACTIVITY];
                break;
        }

        return $ret;
    }

    public function getConditionField(array $componentVariation): ?string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BUTTONWRAPPER_TOGGLEUSERPOSTACTIVITY:
                return 'hasUserpostactivity';
        }

        return null;
    }

    public function getTextfieldModule(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BUTTONWRAPPER_TOGGLEUSERPOSTACTIVITY:
                return [PoP_Module_Processor_FeedButtonInners::class, PoP_Module_Processor_FeedButtonInners::MODULE_BUTTONINNER_TOGGLEUSERPOSTACTIVITY];
        }

        return parent::getTextfieldModule($componentVariation, $props);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BUTTONWRAPPER_TOGGLEUSERPOSTACTIVITY:
                $this->appendProp($componentVariation, $props, 'class', 'pop-collapse-btn');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



