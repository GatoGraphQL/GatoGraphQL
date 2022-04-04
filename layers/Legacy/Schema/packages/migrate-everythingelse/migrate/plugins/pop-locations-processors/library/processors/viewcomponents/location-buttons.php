<?php

class PoP_Module_Processor_LocationViewComponentButtons extends PoP_Module_Processor_LocationViewComponentButtonsBase
{
    public final const MODULE_VIEWCOMPONENT_BUTTON_POSTLOCATIONS = 'em-viewcomponent-button-postlocations';
    public final const MODULE_VIEWCOMPONENT_BUTTON_POSTLOCATIONS_NOINITMARKERS = 'em-viewcomponent-button-postlocations-noinitmarkers';
    public final const MODULE_VIEWCOMPONENT_BUTTON_USERLOCATIONS = 'em-viewcomponent-button-userlocations';
    public final const MODULE_VIEWCOMPONENT_BUTTON_USERLOCATIONS_NOINITMARKERS = 'em-viewcomponent-button-userlocations-noinitmarkers';
    public final const MODULE_VIEWCOMPONENT_BUTTON_POSTSIDEBARLOCATIONS = 'em-viewcomponent-button-postsidebarlocations';
    public final const MODULE_VIEWCOMPONENT_BUTTON_USERSIDEBARLOCATIONS = 'em-viewcomponent-button-usersidebarlocations';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_VIEWCOMPONENT_BUTTON_POSTLOCATIONS],
            [self::class, self::MODULE_VIEWCOMPONENT_BUTTON_POSTLOCATIONS_NOINITMARKERS],
            [self::class, self::MODULE_VIEWCOMPONENT_BUTTON_USERLOCATIONS],
            [self::class, self::MODULE_VIEWCOMPONENT_BUTTON_USERLOCATIONS_NOINITMARKERS],
            [self::class, self::MODULE_VIEWCOMPONENT_BUTTON_POSTSIDEBARLOCATIONS],
            [self::class, self::MODULE_VIEWCOMPONENT_BUTTON_USERSIDEBARLOCATIONS],
        );
    }

    public function initMarkers(array $module)
    {

        // When in the Map window, the location link must not initialize the markers, since they are already initialized by the map itself.
        // Do it so, initializes them twice, which leads to problems, like when searching it displays markers from the previous state
        // (which were initialized then drawn then initialized again and remained there in the memory)
        switch ($module[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_POSTLOCATIONS_NOINITMARKERS:
            case self::MODULE_VIEWCOMPONENT_BUTTON_USERLOCATIONS_NOINITMARKERS:
                return false;
        }

        return parent::initMarkers($module);
    }

    public function getLocationModule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_POSTLOCATIONS:
            case self::MODULE_VIEWCOMPONENT_BUTTON_POSTLOCATIONS_NOINITMARKERS:
            case self::MODULE_VIEWCOMPONENT_BUTTON_USERLOCATIONS:
            case self::MODULE_VIEWCOMPONENT_BUTTON_USERLOCATIONS_NOINITMARKERS:
            case self::MODULE_VIEWCOMPONENT_BUTTON_POSTSIDEBARLOCATIONS:
            case self::MODULE_VIEWCOMPONENT_BUTTON_USERSIDEBARLOCATIONS:
                return [PoP_Module_Processor_LocationViewComponentLinks::class, PoP_Module_Processor_LocationViewComponentLinks::MODULE_VIEWCOMPONENT_LINK_LOCATIONICONNAME];
        }

        return parent::getLocationModule($module);
    }

    public function getLocationComplementModule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_POSTSIDEBARLOCATIONS:
            case self::MODULE_VIEWCOMPONENT_BUTTON_USERSIDEBARLOCATIONS:
                return [PoP_Module_Processor_LocationAddressLayouts::class, PoP_Module_Processor_LocationAddressLayouts::MODULE_EM_LAYOUT_LOCATIONADDRESS];
        }

        return parent::getLocationComplementModule($module);
    }
    public function getJoinSeparator(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_POSTSIDEBARLOCATIONS:
            case self::MODULE_VIEWCOMPONENT_BUTTON_USERSIDEBARLOCATIONS:
                return '<br/>';
        }

        return parent::getJoinSeparator($module);
    }
    public function getEachSeparator(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_POSTSIDEBARLOCATIONS:
            case self::MODULE_VIEWCOMPONENT_BUTTON_USERSIDEBARLOCATIONS:
                return '<br/>';
        }

        return parent::getEachSeparator($module);
    }
    public function getComplementSeparator(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_POSTSIDEBARLOCATIONS:
            case self::MODULE_VIEWCOMPONENT_BUTTON_USERSIDEBARLOCATIONS:
                return '<br/>';
        }

        return parent::getComplementSeparator($module);
    }

    public function getButtoninnerSubmodule(array $module)
    {
        $buttoninners = array(
            self::MODULE_VIEWCOMPONENT_BUTTON_POSTLOCATIONS => [PoP_Module_Processor_LocationViewComponentButtonInners::class, PoP_Module_Processor_LocationViewComponentButtonInners::MODULE_VIEWCOMPONENT_BUTTONINNER_LOCATIONS],
            self::MODULE_VIEWCOMPONENT_BUTTON_POSTLOCATIONS_NOINITMARKERS => [PoP_Module_Processor_LocationViewComponentButtonInners::class, PoP_Module_Processor_LocationViewComponentButtonInners::MODULE_VIEWCOMPONENT_BUTTONINNER_LOCATIONS],
            self::MODULE_VIEWCOMPONENT_BUTTON_USERLOCATIONS => [PoP_Module_Processor_LocationViewComponentButtonInners::class, PoP_Module_Processor_LocationViewComponentButtonInners::MODULE_VIEWCOMPONENT_BUTTONINNER_LOCATIONS],
            self::MODULE_VIEWCOMPONENT_BUTTON_USERLOCATIONS_NOINITMARKERS => [PoP_Module_Processor_LocationViewComponentButtonInners::class, PoP_Module_Processor_LocationViewComponentButtonInners::MODULE_VIEWCOMPONENT_BUTTONINNER_LOCATIONS],
            self::MODULE_VIEWCOMPONENT_BUTTON_POSTSIDEBARLOCATIONS => [PoP_Module_Processor_LocationViewComponentButtonInners::class, PoP_Module_Processor_LocationViewComponentButtonInners::MODULE_VIEWCOMPONENT_BUTTONINNER_LOCATIONS],
            self::MODULE_VIEWCOMPONENT_BUTTON_USERSIDEBARLOCATIONS => [PoP_Module_Processor_LocationViewComponentButtonInners::class, PoP_Module_Processor_LocationViewComponentButtonInners::MODULE_VIEWCOMPONENT_BUTTONINNER_LOCATIONS],
        );

        switch ($module[1]) {
            case self::MODULE_VIEWCOMPONENT_BUTTON_POSTLOCATIONS:
            case self::MODULE_VIEWCOMPONENT_BUTTON_POSTLOCATIONS_NOINITMARKERS:
            case self::MODULE_VIEWCOMPONENT_BUTTON_USERLOCATIONS:
            case self::MODULE_VIEWCOMPONENT_BUTTON_USERLOCATIONS_NOINITMARKERS:
            case self::MODULE_VIEWCOMPONENT_BUTTON_POSTSIDEBARLOCATIONS:
            case self::MODULE_VIEWCOMPONENT_BUTTON_USERSIDEBARLOCATIONS:
                return $buttoninners[$module[1]];
        }

        return parent::getButtoninnerSubmodule($module);
    }

    // function getHeaderSubmodule(array $module) {

    //     switch ($module[1]) {

    //         case self::MODULE_VIEWCOMPONENT_BUTTON_POSTLOCATIONS:
    //         case self::MODULE_VIEWCOMPONENT_BUTTON_POSTLOCATIONS_NOINITMARKERS:
    //         case self::MODULE_VIEWCOMPONENT_BUTTON_POSTSIDEBARLOCATIONS:

    //             return [PoP_Module_Processor_PostViewComponentHeaders::class, PoP_Module_Processor_PostViewComponentHeaders::MODULE_VIEWCOMPONENT_HEADER_POST];

    //         case self::MODULE_VIEWCOMPONENT_BUTTON_USERLOCATIONS:
    //         case self::MODULE_VIEWCOMPONENT_BUTTON_USERLOCATIONS_NOINITMARKERS:
    //         case self::MODULE_VIEWCOMPONENT_BUTTON_USERSIDEBARLOCATIONS:

    //             return [PoP_Module_Processor_UserViewComponentHeaders::class, PoP_Module_Processor_UserViewComponentHeaders::MODULE_VIEWCOMPONENT_HEADER_USER];
    //     }

    //     return parent::getHeaderSubmodule($module);
    // }
}



