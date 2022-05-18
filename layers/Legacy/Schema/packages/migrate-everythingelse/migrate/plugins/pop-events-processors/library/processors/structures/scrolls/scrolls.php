<?php

class PoP_Events_Module_Processor_CustomScrolls extends PoP_Module_Processor_ScrollsBase
{
    public final const MODULE_SCROLL_EVENTS_NAVIGATOR = 'scroll-events-navigator';
    public final const MODULE_SCROLL_PASTEVENTS_NAVIGATOR = 'scroll-pastevents-navigator';
    public final const MODULE_SCROLL_EVENTS_ADDONS = 'scroll-events-addons';
    public final const MODULE_SCROLL_PASTEVENTS_ADDONS = 'scroll-pastevents-addons';
    public final const MODULE_SCROLL_EVENTS_DETAILS = 'scroll-events-details';
    public final const MODULE_SCROLL_PASTEVENTS_DETAILS = 'scroll-pastevents-details';
    public final const MODULE_SCROLL_EVENTS_SIMPLEVIEW = 'scroll-events-simpleview';
    public final const MODULE_SCROLL_PASTEVENTS_SIMPLEVIEW = 'scroll-pastevents-simpleview';
    public final const MODULE_SCROLL_EVENTS_FULLVIEW = 'scroll-events-fullview';
    public final const MODULE_SCROLL_PASTEVENTS_FULLVIEW = 'scroll-pastevents-fullview';
    public final const MODULE_SCROLL_AUTHOREVENTS_FULLVIEW = 'scroll-authorevents-fullview';
    public final const MODULE_SCROLL_AUTHORPASTEVENTS_FULLVIEW = 'scroll-authorpastevents-fullview';
    public final const MODULE_SCROLL_EVENTS_THUMBNAIL = 'scroll-events-thumbnail';
    public final const MODULE_SCROLL_PASTEVENTS_THUMBNAIL = 'scroll-pastevents-thumbnail';
    public final const MODULE_SCROLL_EVENTS_LIST = 'scroll-events-list';
    public final const MODULE_SCROLL_PASTEVENTS_LIST = 'scroll-pastevents-list';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SCROLL_EVENTS_NAVIGATOR],
            [self::class, self::MODULE_SCROLL_PASTEVENTS_NAVIGATOR],
            [self::class, self::MODULE_SCROLL_EVENTS_ADDONS],
            [self::class, self::MODULE_SCROLL_PASTEVENTS_ADDONS],
            [self::class, self::MODULE_SCROLL_EVENTS_DETAILS],
            [self::class, self::MODULE_SCROLL_PASTEVENTS_DETAILS],
            [self::class, self::MODULE_SCROLL_EVENTS_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_PASTEVENTS_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_EVENTS_FULLVIEW],
            [self::class, self::MODULE_SCROLL_PASTEVENTS_FULLVIEW],
            [self::class, self::MODULE_SCROLL_EVENTS_THUMBNAIL],
            [self::class, self::MODULE_SCROLL_PASTEVENTS_THUMBNAIL],
            [self::class, self::MODULE_SCROLL_EVENTS_LIST],
            [self::class, self::MODULE_SCROLL_PASTEVENTS_LIST],
            [self::class, self::MODULE_SCROLL_AUTHOREVENTS_FULLVIEW],
            [self::class, self::MODULE_SCROLL_AUTHORPASTEVENTS_FULLVIEW],
        );
    }


    public function getInnerSubmodule(array $component)
    {
        $inners = array(
            self::MODULE_SCROLL_EVENTS_NAVIGATOR => [PoP_Events_Module_Processor_CustomScrollInners::class, PoP_Events_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_EVENTS_NAVIGATOR],
            self::MODULE_SCROLL_PASTEVENTS_NAVIGATOR => [PoP_Events_Module_Processor_CustomScrollInners::class, PoP_Events_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_PASTEVENTS_NAVIGATOR],
            self::MODULE_SCROLL_EVENTS_ADDONS => [PoP_Events_Module_Processor_CustomScrollInners::class, PoP_Events_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_EVENTS_ADDONS],
            self::MODULE_SCROLL_PASTEVENTS_ADDONS => [PoP_Events_Module_Processor_CustomScrollInners::class, PoP_Events_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_PASTEVENTS_ADDONS],
            self::MODULE_SCROLL_EVENTS_DETAILS => [PoP_Events_Module_Processor_CustomScrollInners::class, PoP_Events_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_EVENTS_DETAILS],
            self::MODULE_SCROLL_PASTEVENTS_DETAILS => [PoP_Events_Module_Processor_CustomScrollInners::class, PoP_Events_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_PASTEVENTS_DETAILS],
            self::MODULE_SCROLL_EVENTS_SIMPLEVIEW => [PoP_Events_Module_Processor_CustomScrollInners::class, PoP_Events_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_EVENTS_SIMPLEVIEW],
            self::MODULE_SCROLL_PASTEVENTS_SIMPLEVIEW => [PoP_Events_Module_Processor_CustomScrollInners::class, PoP_Events_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_PASTEVENTS_SIMPLEVIEW],
            self::MODULE_SCROLL_EVENTS_FULLVIEW => [PoP_Events_Module_Processor_CustomScrollInners::class, PoP_Events_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_EVENTS_FULLVIEW],
            self::MODULE_SCROLL_PASTEVENTS_FULLVIEW => [PoP_Events_Module_Processor_CustomScrollInners::class, PoP_Events_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_PASTEVENTS_FULLVIEW],
            self::MODULE_SCROLL_EVENTS_THUMBNAIL => [PoP_Events_Module_Processor_CustomScrollInners::class, PoP_Events_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_EVENTS_THUMBNAIL],
            self::MODULE_SCROLL_PASTEVENTS_THUMBNAIL => [PoP_Events_Module_Processor_CustomScrollInners::class, PoP_Events_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_PASTEVENTS_THUMBNAIL],
            self::MODULE_SCROLL_EVENTS_LIST => [PoP_Events_Module_Processor_CustomScrollInners::class, PoP_Events_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_EVENTS_LIST],
            self::MODULE_SCROLL_PASTEVENTS_LIST => [PoP_Events_Module_Processor_CustomScrollInners::class, PoP_Events_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_PASTEVENTS_LIST],
            self::MODULE_SCROLL_AUTHOREVENTS_FULLVIEW => [PoP_Events_Module_Processor_CustomScrollInners::class, PoP_Events_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_AUTHOREVENTS_FULLVIEW],
            self::MODULE_SCROLL_AUTHORPASTEVENTS_FULLVIEW => [PoP_Events_Module_Processor_CustomScrollInners::class, PoP_Events_Module_Processor_CustomScrollInners::MODULE_SCROLLINNER_AUTHORPASTEVENTS_FULLVIEW],
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
            [self::class, self::MODULE_SCROLL_PASTEVENTS_THUMBNAIL],
            [self::class, self::MODULE_SCROLL_EVENTS_THUMBNAIL],
        );
        $lists = array(
            [self::class, self::MODULE_SCROLL_PASTEVENTS_LIST],
            [self::class, self::MODULE_SCROLL_EVENTS_LIST],
        );
        $details = array(
            [self::class, self::MODULE_SCROLL_PASTEVENTS_DETAILS],
            [self::class, self::MODULE_SCROLL_EVENTS_DETAILS],
        );
        $navigators = array(
            [self::class, self::MODULE_SCROLL_EVENTS_NAVIGATOR],
            [self::class, self::MODULE_SCROLL_PASTEVENTS_NAVIGATOR],
        );
        $addons = array(
            [self::class, self::MODULE_SCROLL_EVENTS_ADDONS],
            [self::class, self::MODULE_SCROLL_PASTEVENTS_ADDONS],
        );
        $simpleviews = array(
            [self::class, self::MODULE_SCROLL_EVENTS_SIMPLEVIEW],
            [self::class, self::MODULE_SCROLL_PASTEVENTS_SIMPLEVIEW],
        );
        $fullviews = array(
            [self::class, self::MODULE_SCROLL_EVENTS_FULLVIEW],
            [self::class, self::MODULE_SCROLL_PASTEVENTS_FULLVIEW],
            [self::class, self::MODULE_SCROLL_AUTHOREVENTS_FULLVIEW],
            [self::class, self::MODULE_SCROLL_AUTHORPASTEVENTS_FULLVIEW],
        );

        $extra_class = '';
        if (in_array($component, $navigators)) {
            $extra_class = 'navigator text-inverse';
        } elseif (in_array($component, $addons)) {
            $extra_class = 'addons';
        } elseif (in_array($component, $simpleviews)) {
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


        $inner = $this->getInnerSubmodule($component);
        if (in_array($component, $navigators)) {
            // Make it activeItem: highlight on viewing the corresponding fullview
            $this->appendProp($inner, $props, 'class', 'pop-activeitem');
        }

        parent::initModelProps($component, $props);
    }
}


