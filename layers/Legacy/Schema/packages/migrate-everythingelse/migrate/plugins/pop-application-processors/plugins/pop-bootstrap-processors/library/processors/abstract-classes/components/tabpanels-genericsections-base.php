<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\ComponentProcessors\FormattableModuleInterface;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class PoP_Module_Processor_GenericSectionTabPanelComponentsBase extends PoP_Module_Processor_FormatActiveTabPanelComponentsBase
{
    public function getPanelHeaderThumbs(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $format_thumbs = array(
            POP_FORMAT_DETAILS => 'fa-th-list',
            POP_FORMAT_SIMPLEVIEW => 'fa-angle-right',
            POP_FORMAT_FULLVIEW => 'fa-angle-double-right',
            POP_FORMAT_THUMBNAIL => 'fa-th',
            POP_FORMAT_LIST => 'fa-list',
            POP_FORMAT_MAP => 'fa-map-marker',
            POP_FORMAT_CALENDARMAP => 'fa-map-marker',
            POP_FORMAT_CALENDAR => 'fa-calendar',
        );

        $ret = array();
        foreach ($this->getSubcomponents($component) as $subcomponent) {
            $processor = $componentprocessor_manager->getProcessor($subcomponent);
            if ($processor instanceof FormattableModuleInterface) {
                $format = $processor->getFormat($subcomponent);
                if ($thumb = $format_thumbs[$format] ?? null) {
                    $subcomponentFullName = \PoP\ComponentModel\Facades\Modules\ComponentHelpersFacade::getInstance()->getComponentFullName($subcomponent);
                    $ret[$subcomponentFullName] = $thumb;
                }
            }
        }

        if ($ret) {
            return $ret;
        }

        return parent::getPanelHeaderThumbs($component, $props);
    }
    public function getPanelHeaderTitles(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $format_titles = array(
            POP_FORMAT_DETAILS => TranslationAPIFacade::getInstance()->__('Details', 'poptheme-wassup'),
            POP_FORMAT_SIMPLEVIEW => TranslationAPIFacade::getInstance()->__('Feed', 'poptheme-wassup'),
            POP_FORMAT_FULLVIEW => TranslationAPIFacade::getInstance()->__('Full view', 'poptheme-wassup'),
            POP_FORMAT_THUMBNAIL => TranslationAPIFacade::getInstance()->__('Thumbnail', 'poptheme-wassup'),
            POP_FORMAT_LIST => TranslationAPIFacade::getInstance()->__('List', 'poptheme-wassup'),
            POP_FORMAT_MAP => TranslationAPIFacade::getInstance()->__('Map', 'poptheme-wassup'),
            POP_FORMAT_CALENDARMAP => TranslationAPIFacade::getInstance()->__('Map', 'poptheme-wassup'),
            POP_FORMAT_CALENDAR => TranslationAPIFacade::getInstance()->__('Calendar', 'poptheme-wassup'),
        );

        $ret = array();
        foreach ($this->getSubcomponents($component) as $subcomponent) {
            $processor = $componentprocessor_manager->getProcessor($subcomponent);
            if ($processor instanceof FormattableModuleInterface) {
                $format = $processor->getFormat($subcomponent);
                if ($title = $format_titles[$format] ?? null) {
                    $subcomponentFullName = \PoP\ComponentModel\Facades\Modules\ComponentHelpersFacade::getInstance()->getComponentFullName($subcomponent);
                    $ret[$subcomponentFullName] = $title;
                }
            }
        }

        if ($ret) {
            return $ret;
        }

        return parent::getPanelHeaderTitles($component, $props);
    }

    protected function lazyLoadInactivePanels(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return true;
    }
}
