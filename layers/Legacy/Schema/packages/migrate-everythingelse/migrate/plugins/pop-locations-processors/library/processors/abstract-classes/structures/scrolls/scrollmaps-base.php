<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class PoP_Module_Processor_ScrollMapsBase extends PoP_Module_Processor_ScrollsBase
{
    protected function getDescription(array $componentVariation, array &$props)
    {
        $placeholder = '<div class="bg-warning text-warning text-center row scroll-row"><small>%s</small></div>';
        $direction = $this->getProp($componentVariation, $props, 'direction');
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

    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);

        // Enable Waypoints Theater: take over the full screen when reaching the scroll top
        if ($this->getProp($componentVariation, $props, 'theatermap')) {
            $this->addJsmethod($ret, 'waypointsTheater');
        }

        if ($this->getProp($componentVariation, $props, 'scrollable-container')) {
            // Direction
            $direction = $this->getProp($componentVariation, $props, 'direction');
            if ($direction == 'vertical') {
                $this->addJsmethod($ret, 'scrollbarVertical');
            } elseif ($direction == 'horizontal') {
                $this->addJsmethod($ret, 'scrollbarHorizontal');
            }
        }

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        $this->appendProp($componentVariation, $props, 'class', 'mapdetails');

        // Make it activeItem: highlight on viewing the corresponding fullview
        $inner = $this->getInnerSubmodule($componentVariation);
        $this->appendProp($inner, $props, 'class', 'pop-openmapmarkers');

        // By default the scrollmap is vertical
        $this->setProp($componentVariation, $props, 'direction', 'vertical');
        $direction = $this->getProp($componentVariation, $props, 'direction');
        $this->appendProp($componentVariation, $props, 'class', $direction);

        // If the direction is "horizontal", then it must have the scrollable container, or otherwise how to navigate the items?
        if ($direction == 'horizontal') {
            $this->setProp($componentVariation, $props, 'scrollable-container', true);
            $this->setProp($componentVariation, $props, 'theatermap', false);
        }

        // Make it theater by default. It can be overriden, eg: Who we are Map for GetPoP
        $this->setProp($componentVariation, $props, 'theatermap', true);
        if ($this->getProp($componentVariation, $props, 'theatermap')) {
            // Make the offcanvas theater when the scroll reaches top of the page
            $this->appendProp($componentVariation, $props, 'class', 'waypoint');
            $this->mergeProp(
                $componentVariation,
                $props,
                'params',
                array(
                    'data-toggle' => 'theater'
                )
            );
        }

        // Make the list scrollable inside the dimensions of the map
        if ($this->getProp($componentVariation, $props, 'scrollable-container')) {
            $this->appendProp($componentVariation, $props, 'class', 'perfect-scrollbar scrollable');

            // "horizontal" con waypoints is not currently supported
            // if ($direction == 'vertical') {
            $this->appendProp($componentVariation, $props, 'class', 'pop-waypoints-context');
            // }
        }

        parent::initModelProps($componentVariation, $props);
    }
}
