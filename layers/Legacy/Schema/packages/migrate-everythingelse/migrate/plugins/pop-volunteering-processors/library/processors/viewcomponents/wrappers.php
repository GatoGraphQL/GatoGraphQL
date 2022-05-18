<?php

class PoPCore_GenericForms_Module_Processor_ViewComponentButtonWrappers extends PoP_Module_Processor_ConditionWrapperBase
{
    public final const MODULE_VIEWCOMPONENT_COMPACTBUTTONWRAPPER_POST_VOLUNTEER_BIG = 'viewcomponent-postcompactbuttonwrapper-volunteer-big';
    public final const MODULE_VIEWCOMPONENT_BUTTONWRAPPER_POST_VOLUNTEER_BIG = 'viewcomponent-postbuttonwrapper-volunteer-big';
    public final const MODULE_VIEWCOMPONENT_BUTTONWRAPPER_POST_VOLUNTEER_TINY = 'viewcomponent-postbuttonwrapper-volunteer-tiny';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_VIEWCOMPONENT_COMPACTBUTTONWRAPPER_POST_VOLUNTEER_BIG],
            [self::class, self::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_POST_VOLUNTEER_BIG],
            [self::class, self::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_POST_VOLUNTEER_TINY],
        );
    }

    public function getConditionSucceededSubmodules(array $module)
    {
        $ret = parent::getConditionSucceededSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_VIEWCOMPONENT_COMPACTBUTTONWRAPPER_POST_VOLUNTEER_BIG:
                $ret[] = [PoPCore_GenericForms_Module_Processor_PostViewComponentButtons::class, PoPCore_GenericForms_Module_Processor_PostViewComponentButtons::MODULE_VIEWCOMPONENT_COMPACTBUTTON_POST_VOLUNTEER_BIG];
                break;

            case self::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_POST_VOLUNTEER_BIG:
                $ret[] = [PoPCore_GenericForms_Module_Processor_PostViewComponentButtons::class, PoPCore_GenericForms_Module_Processor_PostViewComponentButtons::MODULE_VIEWCOMPONENT_BUTTON_POST_VOLUNTEER_BIG];
                break;

            case self::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_POST_VOLUNTEER_TINY:
                $ret[] = [PoPCore_GenericForms_Module_Processor_PostViewComponentButtons::class, PoPCore_GenericForms_Module_Processor_PostViewComponentButtons::MODULE_VIEWCOMPONENT_BUTTON_POST_VOLUNTEER_TINY];
                break;
        }

        return $ret;
    }

    public function getConditionField(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_VIEWCOMPONENT_COMPACTBUTTONWRAPPER_POST_VOLUNTEER_BIG:
            case self::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_POST_VOLUNTEER_BIG:
            case self::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_POST_VOLUNTEER_TINY:
                return 'volunteersNeeded';
        }

        return null;
    }

    public function getConditionFailedSubmodules(array $module)
    {
        $ret = parent::getConditionFailedSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_VIEWCOMPONENT_COMPACTBUTTONWRAPPER_POST_VOLUNTEER_BIG:
            case self::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_POST_VOLUNTEER_TINY:
                $ret[] = [PoP_Module_Processor_HideIfEmpties::class, PoP_Module_Processor_HideIfEmpties::MODULE_HIDEIFEMPTY];
                break;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_VIEWCOMPONENT_COMPACTBUTTONWRAPPER_POST_VOLUNTEER_BIG:
            case self::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_POST_VOLUNTEER_BIG:
            case self::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_POST_VOLUNTEER_TINY:
                $this->appendProp($module, $props, 'class', 'volunteer-wrapper');
                break;
        }

        parent::initModelProps($module, $props);
    }
}



