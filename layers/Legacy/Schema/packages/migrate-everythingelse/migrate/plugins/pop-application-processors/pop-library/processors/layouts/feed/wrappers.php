<?php

class PoP_Module_Processor_FeedButtonWrappers extends PoP_Module_Processor_ShowIfNotEmptyConditionWrapperBase
{
    public final const MODULE_BUTTONWRAPPER_TOGGLEUSERPOSTACTIVITY = 'buttonwrapper-userpostactivity';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BUTTONWRAPPER_TOGGLEUSERPOSTACTIVITY],
        );
    }

    public function getConditionSucceededSubmodules(array $module)
    {
        $ret = parent::getConditionSucceededSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_BUTTONWRAPPER_TOGGLEUSERPOSTACTIVITY:
                $ret[] = [PoP_Module_Processor_FeedButtons::class, PoP_Module_Processor_FeedButtons::MODULE_BUTTON_TOGGLEUSERPOSTACTIVITY];
                break;
        }

        return $ret;
    }

    public function getConditionField(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_BUTTONWRAPPER_TOGGLEUSERPOSTACTIVITY:
                return 'hasUserpostactivity';
        }

        return null;
    }

    public function getTextfieldModule(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_BUTTONWRAPPER_TOGGLEUSERPOSTACTIVITY:
                return [PoP_Module_Processor_FeedButtonInners::class, PoP_Module_Processor_FeedButtonInners::MODULE_BUTTONINNER_TOGGLEUSERPOSTACTIVITY];
        }

        return parent::getTextfieldModule($module, $props);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_BUTTONWRAPPER_TOGGLEUSERPOSTACTIVITY:
                $this->appendProp($module, $props, 'class', 'pop-collapse-btn');
                break;
        }

        parent::initModelProps($module, $props);
    }
}



