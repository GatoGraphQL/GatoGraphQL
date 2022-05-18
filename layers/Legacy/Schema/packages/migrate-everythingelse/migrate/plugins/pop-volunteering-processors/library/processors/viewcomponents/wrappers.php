<?php

class PoPCore_GenericForms_Module_Processor_ViewComponentButtonWrappers extends PoP_Module_Processor_ConditionWrapperBase
{
    public final const MODULE_VIEWCOMPONENT_COMPACTBUTTONWRAPPER_POST_VOLUNTEER_BIG = 'viewcomponent-postcompactbuttonwrapper-volunteer-big';
    public final const MODULE_VIEWCOMPONENT_BUTTONWRAPPER_POST_VOLUNTEER_BIG = 'viewcomponent-postbuttonwrapper-volunteer-big';
    public final const MODULE_VIEWCOMPONENT_BUTTONWRAPPER_POST_VOLUNTEER_TINY = 'viewcomponent-postbuttonwrapper-volunteer-tiny';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_VIEWCOMPONENT_COMPACTBUTTONWRAPPER_POST_VOLUNTEER_BIG],
            [self::class, self::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_POST_VOLUNTEER_BIG],
            [self::class, self::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_POST_VOLUNTEER_TINY],
        );
    }

    public function getConditionSucceededSubmodules(array $component)
    {
        $ret = parent::getConditionSucceededSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_VIEWCOMPONENT_COMPACTBUTTONWRAPPER_POST_VOLUNTEER_BIG:
                $ret[] = [PoPCore_GenericForms_Module_Processor_PostViewComponentButtons::class, PoPCore_GenericForms_Module_Processor_PostViewComponentButtons::COMPONENT_VIEWCOMPONENT_COMPACTBUTTON_POST_VOLUNTEER_BIG];
                break;

            case self::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_POST_VOLUNTEER_BIG:
                $ret[] = [PoPCore_GenericForms_Module_Processor_PostViewComponentButtons::class, PoPCore_GenericForms_Module_Processor_PostViewComponentButtons::COMPONENT_VIEWCOMPONENT_BUTTON_POST_VOLUNTEER_BIG];
                break;

            case self::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_POST_VOLUNTEER_TINY:
                $ret[] = [PoPCore_GenericForms_Module_Processor_PostViewComponentButtons::class, PoPCore_GenericForms_Module_Processor_PostViewComponentButtons::COMPONENT_VIEWCOMPONENT_BUTTON_POST_VOLUNTEER_TINY];
                break;
        }

        return $ret;
    }

    public function getConditionField(array $component): ?string
    {
        switch ($component[1]) {
            case self::COMPONENT_VIEWCOMPONENT_COMPACTBUTTONWRAPPER_POST_VOLUNTEER_BIG:
            case self::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_POST_VOLUNTEER_BIG:
            case self::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_POST_VOLUNTEER_TINY:
                return 'volunteersNeeded';
        }

        return null;
    }

    public function getConditionFailedSubmodules(array $component)
    {
        $ret = parent::getConditionFailedSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_VIEWCOMPONENT_COMPACTBUTTONWRAPPER_POST_VOLUNTEER_BIG:
            case self::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_POST_VOLUNTEER_TINY:
                $ret[] = [PoP_Module_Processor_HideIfEmpties::class, PoP_Module_Processor_HideIfEmpties::COMPONENT_HIDEIFEMPTY];
                break;
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_VIEWCOMPONENT_COMPACTBUTTONWRAPPER_POST_VOLUNTEER_BIG:
            case self::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_POST_VOLUNTEER_BIG:
            case self::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_POST_VOLUNTEER_TINY:
                $this->appendProp($component, $props, 'class', 'volunteer-wrapper');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



