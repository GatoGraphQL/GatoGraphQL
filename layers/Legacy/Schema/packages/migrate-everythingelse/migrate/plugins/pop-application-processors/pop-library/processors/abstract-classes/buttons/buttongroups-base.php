<?php

use PoP\ComponentModel\Facades\HelperServices\RequestHelperServiceFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class PoP_Module_Processor_CustomButtonGroupsBase extends PoP_Module_Processor_ButtonGroupsBase
{
    public function getHeadersData(array $module, array &$props)
    {
        $formats = $this->getHeadersdataFormats($module, $props);
        
        // All possible titles and icos for PoPTheme Wassup are already set here, however we only need to provide for the correspnding formats, filter out the rest
        $allformats = array_unique(array_merge(array_keys($formats), arrayFlatten(array_values($formats))));
        $alltitles = $this->getHeadersdataTitles($module, $props);
        $allicons = $this->getHeadersdataIcons($module, $props);
        $titles = $icons = array();
        foreach ($allformats as $format) {
            $titles[$format] = $alltitles[$format];
            $icons[$format] = $allicons[$format];
        }
        
        return array(
            'formats' => $formats,
            'titles' => $titles,
            'icons' => $icons,
            'screen' => $this->getHeadersdataScreen($module, $props),
            'url' => $this->getHeadersdataUrl($module, $props),
        );
    }
    protected function getHeadersdataTitles(array $module, array &$props)
    {
        return array(
            POP_FORMAT_SIMPLEVIEW => TranslationAPIFacade::getInstance()->__('Feed', 'poptheme-wassup'),
            POP_FORMAT_FULLVIEW => TranslationAPIFacade::getInstance()->__('Extended', 'poptheme-wassup'),
            POP_FORMAT_LIST => TranslationAPIFacade::getInstance()->__('List', 'poptheme-wassup'),
            POP_FORMAT_THUMBNAIL => TranslationAPIFacade::getInstance()->__('Thumbnail', 'poptheme-wassup'),
            POP_FORMAT_DETAILS => TranslationAPIFacade::getInstance()->__('Details', 'poptheme-wassup'),
            POP_FORMAT_CALENDAR => TranslationAPIFacade::getInstance()->__('Calendar', 'poptheme-wassup'),
            POP_FORMAT_CALENDARMAP => TranslationAPIFacade::getInstance()->__('Map', 'poptheme-wassup'),
            POP_FORMAT_MAP => TranslationAPIFacade::getInstance()->__('Map', 'poptheme-wassup'),
            POP_FORMAT_TABLE => TranslationAPIFacade::getInstance()->__('Edit', 'poptheme-wassup'),
        );
    }
    protected function getHeadersdataIcons(array $module, array &$props)
    {
        return array(
            POP_FORMAT_SIMPLEVIEW => 'fa-angle-right',
            POP_FORMAT_FULLVIEW => 'fa-angle-double-right',
            POP_FORMAT_LIST => 'fa-list',
            POP_FORMAT_THUMBNAIL => 'fa-th',
            POP_FORMAT_DETAILS => 'fa-th-list',
            POP_FORMAT_CALENDAR => 'fa-calendar',
            POP_FORMAT_MAP => 'fa-map-marker',
            POP_FORMAT_CALENDARMAP => 'fa-map-marker',
            POP_FORMAT_TABLE => 'fa-edit',
        );
    }

    protected function getHeadersdataformatsHasmap(array $module, array &$props)
    {
        return false;
    }

    protected function getHeadersdataFormats(array $module, array &$props)
    {

        // We can initially have a common format scheme depending on the screen
        $screen = $this->getHeadersdataScreen($module, $props);
        switch ($screen) {
            case POP_SCREEN_TAGS:
            case POP_SCREEN_AUTHORTAGS:
                $formats = array(
                    POP_FORMAT_DETAILS => array(),
                    POP_FORMAT_LIST => array(),
                );
                return $formats;
            
            case POP_SCREEN_SECTION:
            case POP_SCREEN_AUTHORSECTION:
            case POP_SCREEN_SINGLESECTION:
            case POP_SCREEN_TAGSECTION:
            case POP_SCREEN_HOMESECTION:
                $formats = array(
                    POP_FORMAT_SIMPLEVIEW => array(
                        POP_FORMAT_SIMPLEVIEW,
                        POP_FORMAT_FULLVIEW,
                    )
                );
                // Allow to add the Map (eg: events, projects)
                if (defined('POP_LOCATIONS_INITIALIZED')) {
                    if ($this->getHeadersdataformatsHasmap($module, $props)) {
                        $formats[POP_FORMAT_MAP] = array();
                    }
                }
                $formats[POP_FORMAT_LIST] = array(
                    POP_FORMAT_LIST,
                    POP_FORMAT_THUMBNAIL,
                    POP_FORMAT_DETAILS,
                );
                return $formats;
            
            case POP_SCREEN_USERS:
            case POP_SCREEN_AUTHORUSERS:
            case POP_SCREEN_SINGLEUSERS:
            case POP_SCREEN_TAGUSERS:
                $formats = array(
                    POP_FORMAT_FULLVIEW => array()
                );
                if (defined('POP_LOCATIONS_INITIALIZED')) {
                    if ($this->getHeadersdataformatsHasmap($module, $props)) {
                        $formats[POP_FORMAT_MAP] = array();
                    }
                }
                $formats[POP_FORMAT_DETAILS] = array(
                    POP_FORMAT_LIST,
                    POP_FORMAT_THUMBNAIL,
                    POP_FORMAT_DETAILS,
                );
                return $formats;

            case POP_SCREEN_MYCONTENT:
                return array(
                    POP_FORMAT_TABLE => array(),
                    POP_FORMAT_SIMPLEVIEW => array(
                        POP_FORMAT_SIMPLEVIEW,
                        POP_FORMAT_FULLVIEW,
                    ),
                );
            
            case POP_SCREEN_HIGHLIGHTS:
            case POP_SCREEN_SINGLEHIGHLIGHTS:
                return array(
                    POP_FORMAT_FULLVIEW => array(),
                    POP_FORMAT_LIST => array(
                        POP_FORMAT_LIST,
                        POP_FORMAT_THUMBNAIL,
                    ),
                );

            case POP_SCREEN_MYHIGHLIGHTS:
                return array(
                    POP_FORMAT_TABLE => array(),
                    POP_FORMAT_FULLVIEW => array(),
                );
        }

        if (defined('POP_EVENTS_INITIALIZED')) {
            switch ($screen) {
                case POP_SCREEN_SECTIONCALENDAR:
                case POP_SCREEN_AUTHORSECTIONCALENDAR:
                case POP_SCREEN_TAGSECTIONCALENDAR:
                    return array(
                        POP_FORMAT_CALENDAR => array(),
                        POP_FORMAT_CALENDARMAP => array(),
                    );
            }
        }

        return array();
    }
    protected function getHeadersdataScreen(array $module, array &$props)
    {
        return null;
    }
    protected function getHeadersdataUrl(array $module, array &$props)
    {
        $requestHelperService = RequestHelperServiceFacade::getInstance();
        return $requestHelperService->getCurrentURL();
    }
}
