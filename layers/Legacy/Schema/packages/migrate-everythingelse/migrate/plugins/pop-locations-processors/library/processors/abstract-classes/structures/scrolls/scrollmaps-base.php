<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class PoP_Module_Processor_ScrollMapsBase extends PoP_Module_Processor_ScrollsBase
{
    protected function getDescription(array $component, array &$props)
    {
        $placeholder = '<div class="bg-warning text-warning text-center row scroll-row"><small>%s</small></div>';
        $direction = $this->getProp($component, $props, 'direction');
        if ($direction == 'horizontal') {
            return sprintf(
                $placeholder,
                TranslationAPIFacade::getInstance()->__('Scroll right to load more results', 'poptheme-wassup')
            );
        }

        return sprintf(
            $placeholder,
            TranslationAPIFacade::getInstance()->__('Scroll down to load more results', 'poptheme-wassup')
        );
    }

    public function getJsmethods(array $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        // Enable Waypoints Theater: take over the full screen when reaching the scroll top
        if ($this->getProp($component, $props, 'theatermap')) {
            $this->addJsmethod($ret, 'waypointsTheater');
        }

        if ($this->getProp($component, $props, 'scrollable-container')) {
            // Direction
            $direction = $this->getProp($component, $props, 'direction');
            if ($direction == 'vertical') {
                $this->addJsmethod($ret, 'scrollbarVertical');
            } elseif ($direction == 'horizontal') {
                $this->addJsmethod($ret, 'scrollbarHorizontal');
            }
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        $this->appendProp($component, $props, 'class', 'mapdetails');

        // Make it activeItem: highlight on viewing the corresponding fullview
        $inner = $this->getInnerSubcomponent($component);
        $this->appendProp($inner, $props, 'class', 'pop-openmapmarkers');

        // By default the scrollmap is vertical
        $this->setProp($component, $props, 'direction', 'vertical');
        $direction = $this->getProp($component, $props, 'direction');
        $this->appendProp($component, $props, 'class', $direction);

        // If the direction is "horizontal", then it must have the scrollable container, or otherwise how to navigate the items?
        if ($direction == 'horizontal') {
            $this->setProp($component, $props, 'scrollable-container', true);
            $this->setProp($component, $props, 'theatermap', false);
        }

        // Make it theater by default. It can be overriden, eg: Who we are Map for GetPoP
        $this->setProp($component, $props, 'theatermap', true);
        if ($this->getProp($component, $props, 'theatermap')) {
            // Make the offcanvas theater when the scroll reaches top of the page
            $this->appendProp($component, $props, 'class', 'waypoint');
            $this->mergeProp(
                $component,
                $props,
                'params',
                array(
                    'data-toggle' => 'theater'
                )
            );
        }

        // Make the list scrollable inside the dimensions of the map
        if ($this->getProp($component, $props, 'scrollable-container')) {
            $this->appendProp($component, $props, 'class', 'perfect-scrollbar scrollable');

            // "horizontal" con waypoints is not currently supported
            // if ($direction == 'vertical') {
            $this->appendProp($component, $props, 'class', 'pop-waypoints-context');
            // }
        }

        parent::initModelProps($component, $props);
    }
}
