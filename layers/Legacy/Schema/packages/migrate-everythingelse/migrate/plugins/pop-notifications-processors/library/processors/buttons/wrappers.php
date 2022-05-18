<?php

class GD_AAL_Module_Processor_ButtonWrappers extends PoP_Module_Processor_ConditionWrapperBase
{
    public final const MODULE_AAL_BUTTONWRAPPER_NOTIFICATION_MARKASREAD = 'notifications-buttonwrapper-notification-markasread';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_AAL_BUTTONWRAPPER_NOTIFICATION_MARKASREAD],
        );
    }

    // function getFailedLayouts(array $component) {

    //     $ret = parent::getFailedLayouts($component);

    //     switch ($component[1]) {

    //         case self::COMPONENT_AAL_BUTTONWRAPPER_NOTIFICATION_MARKASREAD:

    //             $ret[] = [AAL_PoPProcessors_Module_Processor_Buttons::class, AAL_PoPProcessors_Module_Processor_Buttons::COMPONENT_AAL_BUTTON_NOTIFICATION_MARKASREAD];
    //             break;
    //     }

    //     return $ret;
    // }

    public function getConditionSucceededSubmodules(array $component)
    {
        $ret = parent::getConditionSucceededSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_AAL_BUTTONWRAPPER_NOTIFICATION_MARKASREAD:
                $ret[] = [AAL_PoPProcessors_Module_Processor_Buttons::class, AAL_PoPProcessors_Module_Processor_Buttons::COMPONENT_AAL_BUTTON_NOTIFICATION_MARKASREAD];
                // $ret[] = [AAL_PoPProcessors_Module_Processor_Buttons::class, AAL_PoPProcessors_Module_Processor_Buttons::COMPONENT_AAL_BUTTON_NOTIFICATION_MARKASUNREAD];
                break;
        }

        return $ret;
    }

    public function getConditionField(array $component): ?string
    {
        switch ($component[1]) {
            case self::COMPONENT_AAL_BUTTONWRAPPER_NOTIFICATION_MARKASREAD:
                return 'isStatusNotRead';
        }

        return null;
    }
}



