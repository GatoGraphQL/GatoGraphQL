<?php

class PoP_Module_Processor_EventDateCarouselIndicatorLayouts extends PoP_Module_Processor_EventDateCarouselIndicatorLayoutsBase
{
    public final const MODULE_EM_LAYOUT_CAROUSEL_INDICATORS_EVENTDATE = 'em-layout-carousel-indicators-eventdate';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_EM_LAYOUT_CAROUSEL_INDICATORS_EVENTDATE],
        );
    }
}



