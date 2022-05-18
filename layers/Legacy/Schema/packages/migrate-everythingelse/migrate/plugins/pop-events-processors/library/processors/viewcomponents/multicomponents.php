<?php

class GD_EM_Module_Processor_EventMultipleComponents extends PoP_Module_Processor_MultiplesBase
{
    public final const COMPONENT_MULTICOMPONENT_EVENT_DATELOCATIONDOWNLOADLINKS = 'multicomponent-event-datelocationdownloadlinks';
    public final const COMPONENT_MULTICOMPONENT_EVENT_DATELOCATION = 'multicomponent-event-datelocation';
    public final const COMPONENT_MULTICOMPONENT_LOCATIONVOLUNTEER = 'multicomponent-locationvolunteer';
    public final const COMPONENT_MULTICOMPONENT_LOCATION = 'multicomponent-location';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_MULTICOMPONENT_EVENT_DATELOCATIONDOWNLOADLINKS],
            [self::class, self::COMPONENT_MULTICOMPONENT_EVENT_DATELOCATION],
            [self::class, self::COMPONENT_MULTICOMPONENT_LOCATIONVOLUNTEER],
            [self::class, self::COMPONENT_MULTICOMPONENT_LOCATION],
        );
    }

    public function getSubcomponents(array $component): array
    {
        $ret = parent::getSubcomponents($component);

        switch ($component[1]) {
            case self::COMPONENT_MULTICOMPONENT_EVENT_DATELOCATIONDOWNLOADLINKS:
                $ret[] = [GD_EM_Module_Processor_DateTimeLayouts::class, GD_EM_Module_Processor_DateTimeLayouts::COMPONENT_EM_LAYOUT_DATETIMEDOWNLOADLINKS];
                $ret[] = [PoP_Module_Processor_LocationViewComponentButtonWrapperss::class, PoP_Module_Processor_LocationViewComponentButtonWrapperss::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS];
                if (defined('POP_VOLUNTEERINGPROCESSORS_INITIALIZED')) {
                    $ret[] = [PoPCore_GenericForms_Module_Processor_ViewComponentButtonWrappers::class, PoPCore_GenericForms_Module_Processor_ViewComponentButtonWrappers::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_POST_VOLUNTEER_TINY];
                }
                break;

            case self::COMPONENT_MULTICOMPONENT_EVENT_DATELOCATION:
                $ret[] = [GD_EM_Module_Processor_DateTimeLayouts::class, GD_EM_Module_Processor_DateTimeLayouts::COMPONENT_EM_LAYOUT_DATETIME];
                $ret[] = [PoP_Module_Processor_LocationViewComponentButtonWrapperss::class, PoP_Module_Processor_LocationViewComponentButtonWrapperss::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS];
                if (defined('POP_VOLUNTEERINGPROCESSORS_INITIALIZED')) {
                    $ret[] = [PoPCore_GenericForms_Module_Processor_ViewComponentButtonWrappers::class, PoPCore_GenericForms_Module_Processor_ViewComponentButtonWrappers::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_POST_VOLUNTEER_TINY];
                }
                break;

            case self::COMPONENT_MULTICOMPONENT_LOCATIONVOLUNTEER:
                $ret[] = [PoP_Module_Processor_LocationViewComponentButtonWrapperss::class, PoP_Module_Processor_LocationViewComponentButtonWrapperss::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS];
                if (defined('POP_VOLUNTEERINGPROCESSORS_INITIALIZED')) {
                    $ret[] = [PoPCore_GenericForms_Module_Processor_ViewComponentButtonWrappers::class, PoPCore_GenericForms_Module_Processor_ViewComponentButtonWrappers::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_POST_VOLUNTEER_TINY];
                }
                break;

            case self::COMPONENT_MULTICOMPONENT_LOCATION:
                $ret[] = [PoP_Module_Processor_LocationViewComponentButtonWrapperss::class, PoP_Module_Processor_LocationViewComponentButtonWrapperss::COMPONENT_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS];
                break;
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_MULTICOMPONENT_EVENT_DATELOCATIONDOWNLOADLINKS:
            case self::COMPONENT_MULTICOMPONENT_EVENT_DATELOCATION:
            case self::COMPONENT_MULTICOMPONENT_LOCATIONVOLUNTEER:
            case self::COMPONENT_MULTICOMPONENT_LOCATION:
                $classes = array(
                    self::COMPONENT_MULTICOMPONENT_EVENT_DATELOCATIONDOWNLOADLINKS => 'event-datelocation',
                    self::COMPONENT_MULTICOMPONENT_EVENT_DATELOCATION => 'event-datelocation',
                    self::COMPONENT_MULTICOMPONENT_LOCATIONVOLUNTEER => 'location',
                    self::COMPONENT_MULTICOMPONENT_LOCATION => 'location',
                );

                $this->appendProp($component, $props, 'class', $classes[$component[1]] ?? null);
                $this->appendProp([PoP_Module_Processor_LocationViewComponentButtons::class, PoP_Module_Processor_LocationViewComponentButtons::COMPONENT_VIEWCOMPONENT_BUTTON_POSTLOCATIONS], $props, 'btn-class', 'btn btn-link btn-nopadding');
                break;
        }

        switch ($component[1]) {
            case self::COMPONENT_MULTICOMPONENT_EVENT_DATELOCATIONDOWNLOADLINKS:
            case self::COMPONENT_MULTICOMPONENT_EVENT_DATELOCATION:
                if (defined('POP_VOLUNTEERINGPROCESSORS_INITIALIZED')) {
                    $this->appendProp([PoPCore_GenericForms_Module_Processor_PostViewComponentButtons::class, PoPCore_GenericForms_Module_Processor_PostViewComponentButtons::COMPONENT_VIEWCOMPONENT_BUTTON_POST_VOLUNTEER_TINY], $props, 'class', 'btn-nopadding');
                }
                break;
        }

        parent::initModelProps($component, $props);
    }
}



