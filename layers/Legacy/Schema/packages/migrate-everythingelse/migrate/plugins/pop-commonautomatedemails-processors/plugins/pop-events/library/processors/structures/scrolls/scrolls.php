<?php

class PoPTheme_Wassup_EM_AE_Module_Processor_Scrolls extends PoP_Module_Processor_ScrollsBase
{
    public final const MODULE_SCROLL_AUTOMATEDEMAILS_EVENTS_DETAILS = 'scroll-automatedemails-events-details';
    public final const MODULE_SCROLL_AUTOMATEDEMAILS_EVENTS_SIMPLEVIEW = 'scroll-automatedemails-events-simpleview';
    public final const MODULE_SCROLL_AUTOMATEDEMAILS_EVENTS_FULLVIEW = 'scroll-automatedemails-events-fullview';
    public final const MODULE_SCROLL_AUTOMATEDEMAILS_EVENTS_THUMBNAIL = 'scroll-automatedemails-events-thumbnail';
    public final const MODULE_SCROLL_AUTOMATEDEMAILS_EVENTS_LIST = 'scroll-automatedemails-events-list';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SCROLL_AUTOMATEDEMAILS_EVENTS_DETAILS],
            [self::class, self::MODULE_SCROLL_AUTOMATEDEMAILS_EVENTS_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_AUTOMATEDEMAILS_EVENTS_FULLVIEW],
            [self::class, self::MODULE_SCROLL_AUTOMATEDEMAILS_EVENTS_THUMBNAIL],
            [self::class, self::MODULE_SCROLL_AUTOMATEDEMAILS_EVENTS_LIST],
        );
    }


    public function getInnerSubmodule(array $component)
    {
        $inners = array(
            self::MODULE_SCROLL_AUTOMATEDEMAILS_EVENTS_DETAILS => [PoPTheme_Wassup_EM_AE_Module_Processor_ScrollInners::class, PoPTheme_Wassup_EM_AE_Module_Processor_ScrollInners::MODULE_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_DETAILS],
            self::MODULE_SCROLL_AUTOMATEDEMAILS_EVENTS_SIMPLEVIEW => [PoPTheme_Wassup_EM_AE_Module_Processor_ScrollInners::class, PoPTheme_Wassup_EM_AE_Module_Processor_ScrollInners::MODULE_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_SIMPLEVIEW],
            self::MODULE_SCROLL_AUTOMATEDEMAILS_EVENTS_FULLVIEW => [PoPTheme_Wassup_EM_AE_Module_Processor_ScrollInners::class, PoPTheme_Wassup_EM_AE_Module_Processor_ScrollInners::MODULE_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_FULLVIEW],
            self::MODULE_SCROLL_AUTOMATEDEMAILS_EVENTS_THUMBNAIL => [PoPTheme_Wassup_EM_AE_Module_Processor_ScrollInners::class, PoPTheme_Wassup_EM_AE_Module_Processor_ScrollInners::MODULE_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_THUMBNAIL],
            self::MODULE_SCROLL_AUTOMATEDEMAILS_EVENTS_LIST => [PoPTheme_Wassup_EM_AE_Module_Processor_ScrollInners::class, PoPTheme_Wassup_EM_AE_Module_Processor_ScrollInners::MODULE_SCROLLINNER_AUTOMATEDEMAILS_EVENTS_LIST],
        );

        if ($inner = $inners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($component);
    }

    public function initModelProps(array $component, array &$props): void
    {

        // Extra classes
        $thumbnails = array(
            [self::class, self::MODULE_SCROLL_AUTOMATEDEMAILS_EVENTS_THUMBNAIL],
        );
        $lists = array(
            [self::class, self::MODULE_SCROLL_AUTOMATEDEMAILS_EVENTS_LIST],
        );
        $details = array(
            [self::class, self::MODULE_SCROLL_AUTOMATEDEMAILS_EVENTS_DETAILS],
        );
        $simpleviews = array(
            [self::class, self::MODULE_SCROLL_AUTOMATEDEMAILS_EVENTS_SIMPLEVIEW],
        );
        $fullviews = array(
            [self::class, self::MODULE_SCROLL_AUTOMATEDEMAILS_EVENTS_FULLVIEW],
        );

        $extra_class = '';
        if (in_array($component, $simpleviews)) {
            $extra_class = 'simpleview';
        } elseif (in_array($component, $fullviews)) {
            $extra_class = 'fullview';
        } elseif (in_array($component, $details)) {
            $extra_class = 'details';
        } elseif (in_array($component, $thumbnails)) {
            $extra_class = 'thumb';
        } elseif (in_array($component, $lists)) {
            $extra_class = 'list';
        }
        $this->appendProp($component, $props, 'class', $extra_class);

        parent::initModelProps($component, $props);
    }
}


