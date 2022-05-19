<?php

class PoP_Module_Processor_LocationViewComponentButtonWrapperss extends PoP_Module_Processor_ConditionWrapperBase
{
    public final const COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS = 'viewcomponent-buttonwrapper-postlocations';
    public final const COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS_NOINITMARKERS = 'viewcomponent-buttonwrapper-postlocations-noinitmarkers';
    public final const COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_USERLOCATIONS = 'viewcomponent-buttonwrapper-userlocations';
    public final const COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_USERLOCATIONS_NOINITMARKERS = 'viewcomponent-buttonwrapper-userlocations-noinitmarkers';
    public final const COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_POSTSIDEBARLOCATIONS = 'viewcomponent-buttonwrapper-postsidebarlocations';
    public final const COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_USERSIDEBARLOCATIONS = 'viewcomponent-buttonwrapper-usersidebarlocations';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS],
            [self::class, self::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS_NOINITMARKERS],
            [self::class, self::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_USERLOCATIONS],
            [self::class, self::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_USERLOCATIONS_NOINITMARKERS],
            [self::class, self::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_POSTSIDEBARLOCATIONS],
            [self::class, self::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_USERSIDEBARLOCATIONS],
        );
    }

    public function getConditionSucceededSubcomponents(array $component)
    {
        $ret = parent::getConditionSucceededSubcomponents($component);

        switch ($component[1]) {
            case self::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS:
                $ret[] = [PoP_Module_Processor_LocationViewComponentButtons::class, PoP_Module_Processor_LocationViewComponentButtons::COMPONENT_VIEWCOMPONENT_BUTTON_POSTLOCATIONS];
                break;

            case self::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS_NOINITMARKERS:
                $ret[] = [PoP_Module_Processor_LocationViewComponentButtons::class, PoP_Module_Processor_LocationViewComponentButtons::COMPONENT_VIEWCOMPONENT_BUTTON_POSTLOCATIONS_NOINITMARKERS];
                break;

            case self::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_USERLOCATIONS:
                $ret[] = [PoP_Module_Processor_LocationViewComponentButtons::class, PoP_Module_Processor_LocationViewComponentButtons::COMPONENT_VIEWCOMPONENT_BUTTON_USERLOCATIONS];
                break;

            case self::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_USERLOCATIONS_NOINITMARKERS:
                $ret[] = [PoP_Module_Processor_LocationViewComponentButtons::class, PoP_Module_Processor_LocationViewComponentButtons::COMPONENT_VIEWCOMPONENT_BUTTON_USERLOCATIONS_NOINITMARKERS];
                break;

            case self::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_POSTSIDEBARLOCATIONS:
                $ret[] = [PoP_Module_Processor_LocationViewComponentButtons::class, PoP_Module_Processor_LocationViewComponentButtons::COMPONENT_VIEWCOMPONENT_BUTTON_POSTSIDEBARLOCATIONS];
                break;

            case self::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_USERSIDEBARLOCATIONS:
                $ret[] = [PoP_Module_Processor_LocationViewComponentButtons::class, PoP_Module_Processor_LocationViewComponentButtons::COMPONENT_VIEWCOMPONENT_BUTTON_USERSIDEBARLOCATIONS];
                break;
        }

        return $ret;
    }

    public function getConditionField(array $component): ?string
    {
        switch ($component[1]) {
            case self::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS:
            case self::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS_NOINITMARKERS:
            case self::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_USERLOCATIONS:
            case self::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_USERLOCATIONS_NOINITMARKERS:
            case self::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_POSTSIDEBARLOCATIONS:
            case self::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_USERSIDEBARLOCATIONS:
                return 'hasLocation';
        }

        return null;
    }

    public function getConditionFailedSubcomponents(array $component)
    {
        $ret = parent::getConditionFailedSubcomponents($component);

        switch ($component[1]) {
            case self::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS:
            case self::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS_NOINITMARKERS:
            case self::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_USERLOCATIONS:
            case self::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_USERLOCATIONS_NOINITMARKERS:
            case self::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_POSTSIDEBARLOCATIONS:
            case self::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_USERSIDEBARLOCATIONS:
                $ret[] = [GD_EM_Module_Processor_WidgetMessages::class, GD_EM_Module_Processor_WidgetMessages::COMPONENT_EM_MESSAGE_NOLOCATION];
                break;
        }

        return $ret;
    }
}



