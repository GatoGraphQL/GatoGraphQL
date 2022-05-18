<?php

class PoP_Module_Processor_LocationViewComponentButtonWrapperss extends PoP_Module_Processor_ConditionWrapperBase
{
    public final const MODULE_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS = 'viewcomponent-buttonwrapper-postlocations';
    public final const MODULE_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS_NOINITMARKERS = 'viewcomponent-buttonwrapper-postlocations-noinitmarkers';
    public final const MODULE_VIEWCOMPONENT_BUTTONWRAPPER_USERLOCATIONS = 'viewcomponent-buttonwrapper-userlocations';
    public final const MODULE_VIEWCOMPONENT_BUTTONWRAPPER_USERLOCATIONS_NOINITMARKERS = 'viewcomponent-buttonwrapper-userlocations-noinitmarkers';
    public final const MODULE_VIEWCOMPONENT_BUTTONWRAPPER_POSTSIDEBARLOCATIONS = 'viewcomponent-buttonwrapper-postsidebarlocations';
    public final const MODULE_VIEWCOMPONENT_BUTTONWRAPPER_USERSIDEBARLOCATIONS = 'viewcomponent-buttonwrapper-usersidebarlocations';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS],
            [self::class, self::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS_NOINITMARKERS],
            [self::class, self::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_USERLOCATIONS],
            [self::class, self::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_USERLOCATIONS_NOINITMARKERS],
            [self::class, self::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_POSTSIDEBARLOCATIONS],
            [self::class, self::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_USERSIDEBARLOCATIONS],
        );
    }

    public function getConditionSucceededSubmodules(array $componentVariation)
    {
        $ret = parent::getConditionSucceededSubmodules($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS:
                $ret[] = [PoP_Module_Processor_LocationViewComponentButtons::class, PoP_Module_Processor_LocationViewComponentButtons::MODULE_VIEWCOMPONENT_BUTTON_POSTLOCATIONS];
                break;

            case self::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS_NOINITMARKERS:
                $ret[] = [PoP_Module_Processor_LocationViewComponentButtons::class, PoP_Module_Processor_LocationViewComponentButtons::MODULE_VIEWCOMPONENT_BUTTON_POSTLOCATIONS_NOINITMARKERS];
                break;

            case self::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_USERLOCATIONS:
                $ret[] = [PoP_Module_Processor_LocationViewComponentButtons::class, PoP_Module_Processor_LocationViewComponentButtons::MODULE_VIEWCOMPONENT_BUTTON_USERLOCATIONS];
                break;

            case self::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_USERLOCATIONS_NOINITMARKERS:
                $ret[] = [PoP_Module_Processor_LocationViewComponentButtons::class, PoP_Module_Processor_LocationViewComponentButtons::MODULE_VIEWCOMPONENT_BUTTON_USERLOCATIONS_NOINITMARKERS];
                break;

            case self::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_POSTSIDEBARLOCATIONS:
                $ret[] = [PoP_Module_Processor_LocationViewComponentButtons::class, PoP_Module_Processor_LocationViewComponentButtons::MODULE_VIEWCOMPONENT_BUTTON_POSTSIDEBARLOCATIONS];
                break;

            case self::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_USERSIDEBARLOCATIONS:
                $ret[] = [PoP_Module_Processor_LocationViewComponentButtons::class, PoP_Module_Processor_LocationViewComponentButtons::MODULE_VIEWCOMPONENT_BUTTON_USERSIDEBARLOCATIONS];
                break;
        }

        return $ret;
    }

    public function getConditionField(array $componentVariation): ?string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS:
            case self::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS_NOINITMARKERS:
            case self::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_USERLOCATIONS:
            case self::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_USERLOCATIONS_NOINITMARKERS:
            case self::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_POSTSIDEBARLOCATIONS:
            case self::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_USERSIDEBARLOCATIONS:
                return 'hasLocation';
        }

        return null;
    }

    public function getConditionFailedSubmodules(array $componentVariation)
    {
        $ret = parent::getConditionFailedSubmodules($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS:
            case self::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS_NOINITMARKERS:
            case self::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_USERLOCATIONS:
            case self::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_USERLOCATIONS_NOINITMARKERS:
            case self::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_POSTSIDEBARLOCATIONS:
            case self::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_USERSIDEBARLOCATIONS:
                $ret[] = [GD_EM_Module_Processor_WidgetMessages::class, GD_EM_Module_Processor_WidgetMessages::MODULE_EM_MESSAGE_NOLOCATION];
                break;
        }

        return $ret;
    }
}



