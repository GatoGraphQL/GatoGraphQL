<?php

class PoP_Module_Processor_CustomCarousels extends PoP_Module_Processor_CarouselsBase
{
    public final const MODULE_CAROUSEL_USERS = 'carousel-users';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CAROUSEL_USERS],
        );
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::MODULE_CAROUSEL_USERS:

                $this->appendProp($component, $props, 'class', 'slide');
                $this->appendProp($component, $props, 'class', 'widget widget-info');
                break;
        }

        parent::initModelProps($component, $props);
    }

    public function getInnerSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_CAROUSEL_USERS:
                return [PoP_Module_Processor_CustomCarouselInners::class, PoP_Module_Processor_CustomCarouselInners::MODULE_CAROUSELINNER_USERS];
        }

        return parent::getInnerSubmodule($component);
    }

    public function getMode(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_CAROUSEL_USERS:

                return 'static';
        }

        return parent::getMode($component, $props);
    }


    public function getControlsTopSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_CAROUSEL_USERS:
                return [PoP_Module_Processor_CustomCarouselControls::class, PoP_Module_Processor_CustomCarouselControls::MODULE_CAROUSELCONTROLS_USERS];
        }

        return parent::getControlsTopSubmodule($component);
    }
}


