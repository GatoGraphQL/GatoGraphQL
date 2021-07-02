<?php

class GD_EM_Module_Processor_EventMultipleComponents extends PoP_Module_Processor_MultiplesBase
{
    public const MODULE_MULTICOMPONENT_EVENT_DATELOCATIONDOWNLOADLINKS = 'multicomponent-event-datelocationdownloadlinks';
    public const MODULE_MULTICOMPONENT_EVENT_DATELOCATION = 'multicomponent-event-datelocation';
    public const MODULE_MULTICOMPONENT_LOCATIONVOLUNTEER = 'multicomponent-locationvolunteer';
    public const MODULE_MULTICOMPONENT_LOCATION = 'multicomponent-location';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_MULTICOMPONENT_EVENT_DATELOCATIONDOWNLOADLINKS],
            [self::class, self::MODULE_MULTICOMPONENT_EVENT_DATELOCATION],
            [self::class, self::MODULE_MULTICOMPONENT_LOCATIONVOLUNTEER],
            [self::class, self::MODULE_MULTICOMPONENT_LOCATION],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_MULTICOMPONENT_EVENT_DATELOCATIONDOWNLOADLINKS:
                $ret[] = [GD_EM_Module_Processor_DateTimeLayouts::class, GD_EM_Module_Processor_DateTimeLayouts::MODULE_EM_LAYOUT_DATETIMEDOWNLOADLINKS];
                $ret[] = [PoP_Module_Processor_LocationViewComponentButtonWrapperss::class, PoP_Module_Processor_LocationViewComponentButtonWrapperss::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS];
                if (defined('POP_VOLUNTEERINGPROCESSORS_INITIALIZED')) {
                    $ret[] = [PoPCore_GenericForms_Module_Processor_ViewComponentButtonWrappers::class, PoPCore_GenericForms_Module_Processor_ViewComponentButtonWrappers::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_POST_VOLUNTEER_TINY];
                }
                break;

            case self::MODULE_MULTICOMPONENT_EVENT_DATELOCATION:
                $ret[] = [GD_EM_Module_Processor_DateTimeLayouts::class, GD_EM_Module_Processor_DateTimeLayouts::MODULE_EM_LAYOUT_DATETIME];
                $ret[] = [PoP_Module_Processor_LocationViewComponentButtonWrapperss::class, PoP_Module_Processor_LocationViewComponentButtonWrapperss::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS];
                if (defined('POP_VOLUNTEERINGPROCESSORS_INITIALIZED')) {
                    $ret[] = [PoPCore_GenericForms_Module_Processor_ViewComponentButtonWrappers::class, PoPCore_GenericForms_Module_Processor_ViewComponentButtonWrappers::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_POST_VOLUNTEER_TINY];
                }
                break;

            case self::MODULE_MULTICOMPONENT_LOCATIONVOLUNTEER:
                $ret[] = [PoP_Module_Processor_LocationViewComponentButtonWrapperss::class, PoP_Module_Processor_LocationViewComponentButtonWrapperss::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS];
                if (defined('POP_VOLUNTEERINGPROCESSORS_INITIALIZED')) {
                    $ret[] = [PoPCore_GenericForms_Module_Processor_ViewComponentButtonWrappers::class, PoPCore_GenericForms_Module_Processor_ViewComponentButtonWrappers::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_POST_VOLUNTEER_TINY];
                }
                break;

            case self::MODULE_MULTICOMPONENT_LOCATION:
                $ret[] = [PoP_Module_Processor_LocationViewComponentButtonWrapperss::class, PoP_Module_Processor_LocationViewComponentButtonWrapperss::MODULE_VIEWCOMPONENT_BUTTONWRAPPER_POSTLOCATIONS];
                break;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_MULTICOMPONENT_EVENT_DATELOCATIONDOWNLOADLINKS:
            case self::MODULE_MULTICOMPONENT_EVENT_DATELOCATION:
            case self::MODULE_MULTICOMPONENT_LOCATIONVOLUNTEER:
            case self::MODULE_MULTICOMPONENT_LOCATION:
                $classes = array(
                    self::MODULE_MULTICOMPONENT_EVENT_DATELOCATIONDOWNLOADLINKS => 'event-datelocation',
                    self::MODULE_MULTICOMPONENT_EVENT_DATELOCATION => 'event-datelocation',
                    self::MODULE_MULTICOMPONENT_LOCATIONVOLUNTEER => 'location',
                    self::MODULE_MULTICOMPONENT_LOCATION => 'location',
                );

                $this->appendProp($module, $props, 'class', $classes[$module[1]] ?? null);
                $this->appendProp([PoP_Module_Processor_LocationViewComponentButtons::class, PoP_Module_Processor_LocationViewComponentButtons::MODULE_VIEWCOMPONENT_BUTTON_POSTLOCATIONS], $props, 'btn-class', 'btn btn-link btn-nopadding');
                break;
        }

        switch ($module[1]) {
            case self::MODULE_MULTICOMPONENT_EVENT_DATELOCATIONDOWNLOADLINKS:
            case self::MODULE_MULTICOMPONENT_EVENT_DATELOCATION:
                if (defined('POP_VOLUNTEERINGPROCESSORS_INITIALIZED')) {
                    $this->appendProp([PoPCore_GenericForms_Module_Processor_PostViewComponentButtons::class, PoPCore_GenericForms_Module_Processor_PostViewComponentButtons::MODULE_VIEWCOMPONENT_BUTTON_POST_VOLUNTEER_TINY], $props, 'class', 'btn-nopadding');
                }
                break;
        }

        parent::initModelProps($module, $props);
    }
}



