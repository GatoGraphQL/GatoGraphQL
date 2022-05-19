<?php

class PoP_Module_Processor_LocationViewComponentButtons extends PoP_Module_Processor_LocationViewComponentButtonsBase
{
    public final const COMPONENT_VIEWCOMPONENT_BUTTON_POSTLOCATIONS = 'em-viewcomponent-button-postlocations';
    public final const COMPONENT_VIEWCOMPONENT_BUTTON_POSTLOCATIONS_NOINITMARKERS = 'em-viewcomponent-button-postlocations-noinitmarkers';
    public final const COMPONENT_VIEWCOMPONENT_BUTTON_USERLOCATIONS = 'em-viewcomponent-button-userlocations';
    public final const COMPONENT_VIEWCOMPONENT_BUTTON_USERLOCATIONS_NOINITMARKERS = 'em-viewcomponent-button-userlocations-noinitmarkers';
    public final const COMPONENT_VIEWCOMPONENT_BUTTON_POSTSIDEBARLOCATIONS = 'em-viewcomponent-button-postsidebarlocations';
    public final const COMPONENT_VIEWCOMPONENT_BUTTON_USERSIDEBARLOCATIONS = 'em-viewcomponent-button-usersidebarlocations';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_VIEWCOMPONENT_BUTTON_POSTLOCATIONS],
            [self::class, self::COMPONENT_VIEWCOMPONENT_BUTTON_POSTLOCATIONS_NOINITMARKERS],
            [self::class, self::COMPONENT_VIEWCOMPONENT_BUTTON_USERLOCATIONS],
            [self::class, self::COMPONENT_VIEWCOMPONENT_BUTTON_USERLOCATIONS_NOINITMARKERS],
            [self::class, self::COMPONENT_VIEWCOMPONENT_BUTTON_POSTSIDEBARLOCATIONS],
            [self::class, self::COMPONENT_VIEWCOMPONENT_BUTTON_USERSIDEBARLOCATIONS],
        );
    }

    public function initMarkers(array $component)
    {

        // When in the Map window, the location link must not initialize the markers, since they are already initialized by the map itself.
        // Do it so, initializes them twice, which leads to problems, like when searching it displays markers from the previous state
        // (which were initialized then drawn then initialized again and remained there in the memory)
        switch ($component[1]) {
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_POSTLOCATIONS_NOINITMARKERS:
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_USERLOCATIONS_NOINITMARKERS:
                return false;
        }

        return parent::initMarkers($component);
    }

    public function getLocationModule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_POSTLOCATIONS:
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_POSTLOCATIONS_NOINITMARKERS:
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_USERLOCATIONS:
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_USERLOCATIONS_NOINITMARKERS:
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_POSTSIDEBARLOCATIONS:
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_USERSIDEBARLOCATIONS:
                return [PoP_Module_Processor_LocationViewComponentLinks::class, PoP_Module_Processor_LocationViewComponentLinks::COMPONENT_VIEWCOMPONENT_LINK_LOCATIONICONNAME];
        }

        return parent::getLocationModule($component);
    }

    public function getLocationComplementModule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_POSTSIDEBARLOCATIONS:
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_USERSIDEBARLOCATIONS:
                return [PoP_Module_Processor_LocationAddressLayouts::class, PoP_Module_Processor_LocationAddressLayouts::COMPONENT_EM_LAYOUT_LOCATIONADDRESS];
        }

        return parent::getLocationComplementModule($component);
    }
    public function getJoinSeparator(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_POSTSIDEBARLOCATIONS:
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_USERSIDEBARLOCATIONS:
                return '<br/>';
        }

        return parent::getJoinSeparator($component);
    }
    public function getEachSeparator(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_POSTSIDEBARLOCATIONS:
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_USERSIDEBARLOCATIONS:
                return '<br/>';
        }

        return parent::getEachSeparator($component);
    }
    public function getComplementSeparator(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_POSTSIDEBARLOCATIONS:
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_USERSIDEBARLOCATIONS:
                return '<br/>';
        }

        return parent::getComplementSeparator($component);
    }

    public function getButtoninnerSubcomponent(array $component)
    {
        $buttoninners = array(
            self::COMPONENT_VIEWCOMPONENT_BUTTON_POSTLOCATIONS => [PoP_Module_Processor_LocationViewComponentButtonInners::class, PoP_Module_Processor_LocationViewComponentButtonInners::COMPONENT_VIEWCOMPONENT_BUTTONINNER_LOCATIONS],
            self::COMPONENT_VIEWCOMPONENT_BUTTON_POSTLOCATIONS_NOINITMARKERS => [PoP_Module_Processor_LocationViewComponentButtonInners::class, PoP_Module_Processor_LocationViewComponentButtonInners::COMPONENT_VIEWCOMPONENT_BUTTONINNER_LOCATIONS],
            self::COMPONENT_VIEWCOMPONENT_BUTTON_USERLOCATIONS => [PoP_Module_Processor_LocationViewComponentButtonInners::class, PoP_Module_Processor_LocationViewComponentButtonInners::COMPONENT_VIEWCOMPONENT_BUTTONINNER_LOCATIONS],
            self::COMPONENT_VIEWCOMPONENT_BUTTON_USERLOCATIONS_NOINITMARKERS => [PoP_Module_Processor_LocationViewComponentButtonInners::class, PoP_Module_Processor_LocationViewComponentButtonInners::COMPONENT_VIEWCOMPONENT_BUTTONINNER_LOCATIONS],
            self::COMPONENT_VIEWCOMPONENT_BUTTON_POSTSIDEBARLOCATIONS => [PoP_Module_Processor_LocationViewComponentButtonInners::class, PoP_Module_Processor_LocationViewComponentButtonInners::COMPONENT_VIEWCOMPONENT_BUTTONINNER_LOCATIONS],
            self::COMPONENT_VIEWCOMPONENT_BUTTON_USERSIDEBARLOCATIONS => [PoP_Module_Processor_LocationViewComponentButtonInners::class, PoP_Module_Processor_LocationViewComponentButtonInners::COMPONENT_VIEWCOMPONENT_BUTTONINNER_LOCATIONS],
        );

        switch ($component[1]) {
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_POSTLOCATIONS:
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_POSTLOCATIONS_NOINITMARKERS:
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_USERLOCATIONS:
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_USERLOCATIONS_NOINITMARKERS:
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_POSTSIDEBARLOCATIONS:
            case self::COMPONENT_VIEWCOMPONENT_BUTTON_USERSIDEBARLOCATIONS:
                return $buttoninners[$component[1]];
        }

        return parent::getButtoninnerSubcomponent($component);
    }

    // function getHeaderSubcomponent(array $component) {

    //     switch ($component[1]) {

    //         case self::COMPONENT_VIEWCOMPONENT_BUTTON_POSTLOCATIONS:
    //         case self::COMPONENT_VIEWCOMPONENT_BUTTON_POSTLOCATIONS_NOINITMARKERS:
    //         case self::COMPONENT_VIEWCOMPONENT_BUTTON_POSTSIDEBARLOCATIONS:

    //             return [PoP_Module_Processor_PostViewComponentHeaders::class, PoP_Module_Processor_PostViewComponentHeaders::COMPONENT_VIEWCOMPONENT_HEADER_POST];

    //         case self::COMPONENT_VIEWCOMPONENT_BUTTON_USERLOCATIONS:
    //         case self::COMPONENT_VIEWCOMPONENT_BUTTON_USERLOCATIONS_NOINITMARKERS:
    //         case self::COMPONENT_VIEWCOMPONENT_BUTTON_USERSIDEBARLOCATIONS:

    //             return [PoP_Module_Processor_UserViewComponentHeaders::class, PoP_Module_Processor_UserViewComponentHeaders::COMPONENT_VIEWCOMPONENT_HEADER_USER];
    //     }

    //     return parent::getHeaderSubcomponent($component);
    // }
}



