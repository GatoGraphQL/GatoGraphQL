<?php

class GD_EM_Module_Processor_CustomCarouselInners extends PoP_Module_Processor_CarouselInnersBase
{
    public final const MODULE_CAROUSELINNER_EVENTS = 'carouselinner-events';
    public final const MODULE_CAROUSELINNER_AUTHOREVENTS = 'carouselinner-authorevents';
    public final const MODULE_CAROUSELINNER_TAGEVENTS = 'carouselinner-tagevents';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CAROUSELINNER_EVENTS],
            [self::class, self::MODULE_CAROUSELINNER_AUTHOREVENTS],
            [self::class, self::MODULE_CAROUSELINNER_TAGEVENTS],
        );
    }

    public function getLayoutGrid(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CAROUSELINNER_EVENTS:
            case self::MODULE_CAROUSELINNER_AUTHOREVENTS:
            case self::MODULE_CAROUSELINNER_TAGEVENTS:
                return \PoP\Root\App::applyFilters(
                    'GD_EM_Module_Processor_CustomCarouselInners:grid',
                    array(
                        'row-items' => 1,
                        'class' => 'col-sm-12',
                        'divider' => 3,
                    )
                );
        }

        return parent::getLayoutGrid($componentVariation, $props);
    }

    public function getLayoutSubmodules(array $componentVariation)
    {
        $ret = parent::getLayoutSubmodules($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_CAROUSELINNER_EVENTS:
            case self::MODULE_CAROUSELINNER_AUTHOREVENTS:
            case self::MODULE_CAROUSELINNER_TAGEVENTS:
                // Allow to override. Eg: TPP Debate needs a different format
                $layout = \PoP\Root\App::applyFilters(
                    'GD_EM_Module_Processor_CustomCarouselInners:layout', 
                    [GD_EM_Module_Processor_CustomPreviewPostLayouts::class, GD_EM_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_EVENT_LIST], 
                    $componentVariation
                );
                $ret[] = $layout;
                break;
        }

        return $ret;
    }
}


