<?php

class PoP_Module_Processor_EventDateCarouselIndicatorLayouts extends PoP_Module_Processor_EventDateCarouselIndicatorLayoutsBase
{
    public final const COMPONENT_EM_LAYOUT_CAROUSEL_INDICATORS_EVENTDATE = 'em-layout-carousel-indicators-eventdate';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_EM_LAYOUT_CAROUSEL_INDICATORS_EVENTDATE,
        );
    }
}



