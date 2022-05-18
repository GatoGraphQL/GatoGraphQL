<?php

class GD_EM_Module_Processor_CustomCarouselInners extends PoP_Module_Processor_CarouselInnersBase
{
    public final const MODULE_CAROUSELINNER_EVENTS = 'carouselinner-events';
    public final const MODULE_CAROUSELINNER_AUTHOREVENTS = 'carouselinner-authorevents';
    public final const MODULE_CAROUSELINNER_TAGEVENTS = 'carouselinner-tagevents';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_CAROUSELINNER_EVENTS],
            [self::class, self::COMPONENT_CAROUSELINNER_AUTHOREVENTS],
            [self::class, self::COMPONENT_CAROUSELINNER_TAGEVENTS],
        );
    }

    public function getLayoutGrid(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_CAROUSELINNER_EVENTS:
            case self::COMPONENT_CAROUSELINNER_AUTHOREVENTS:
            case self::COMPONENT_CAROUSELINNER_TAGEVENTS:
                return \PoP\Root\App::applyFilters(
                    'GD_EM_Module_Processor_CustomCarouselInners:grid',
                    array(
                        'row-items' => 1,
                        'class' => 'col-sm-12',
                        'divider' => 3,
                    )
                );
        }

        return parent::getLayoutGrid($component, $props);
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_CAROUSELINNER_EVENTS:
            case self::COMPONENT_CAROUSELINNER_AUTHOREVENTS:
            case self::COMPONENT_CAROUSELINNER_TAGEVENTS:
                // Allow to override. Eg: TPP Debate needs a different format
                $layout = \PoP\Root\App::applyFilters(
                    'GD_EM_Module_Processor_CustomCarouselInners:layout', 
                    [GD_EM_Module_Processor_CustomPreviewPostLayouts::class, GD_EM_Module_Processor_CustomPreviewPostLayouts::COMPONENT_LAYOUT_PREVIEWPOST_EVENT_LIST], 
                    $component
                );
                $ret[] = $layout;
                break;
        }

        return $ret;
    }
}


