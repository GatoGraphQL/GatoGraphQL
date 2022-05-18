<?php

class GD_AAL_Module_Processor_ButtonWrappers extends PoP_Module_Processor_ConditionWrapperBase
{
    public final const MODULE_AAL_BUTTONWRAPPER_NOTIFICATION_MARKASREAD = 'notifications-buttonwrapper-notification-markasread';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_AAL_BUTTONWRAPPER_NOTIFICATION_MARKASREAD],
        );
    }

    // function getFailedLayouts(array $module) {

    //     $ret = parent::getFailedLayouts($module);

    //     switch ($module[1]) {

    //         case self::MODULE_AAL_BUTTONWRAPPER_NOTIFICATION_MARKASREAD:

    //             $ret[] = [AAL_PoPProcessors_Module_Processor_Buttons::class, AAL_PoPProcessors_Module_Processor_Buttons::MODULE_AAL_BUTTON_NOTIFICATION_MARKASREAD];
    //             break;
    //     }

    //     return $ret;
    // }

    public function getConditionSucceededSubmodules(array $module)
    {
        $ret = parent::getConditionSucceededSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_AAL_BUTTONWRAPPER_NOTIFICATION_MARKASREAD:
                $ret[] = [AAL_PoPProcessors_Module_Processor_Buttons::class, AAL_PoPProcessors_Module_Processor_Buttons::MODULE_AAL_BUTTON_NOTIFICATION_MARKASREAD];
                // $ret[] = [AAL_PoPProcessors_Module_Processor_Buttons::class, AAL_PoPProcessors_Module_Processor_Buttons::MODULE_AAL_BUTTON_NOTIFICATION_MARKASUNREAD];
                break;
        }

        return $ret;
    }

    public function getConditionField(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_AAL_BUTTONWRAPPER_NOTIFICATION_MARKASREAD:
                return 'isStatusNotRead';
        }

        return null;
    }
}



