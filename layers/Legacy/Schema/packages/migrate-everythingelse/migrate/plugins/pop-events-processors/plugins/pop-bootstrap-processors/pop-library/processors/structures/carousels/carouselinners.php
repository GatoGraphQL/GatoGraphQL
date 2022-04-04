<?php

class GD_EM_Module_Processor_CustomCarouselInners extends PoP_Module_Processor_CarouselInnersBase
{
    public final const MODULE_CAROUSELINNER_EVENTS = 'carouselinner-events';
    public final const MODULE_CAROUSELINNER_AUTHOREVENTS = 'carouselinner-authorevents';
    public final const MODULE_CAROUSELINNER_TAGEVENTS = 'carouselinner-tagevents';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CAROUSELINNER_EVENTS],
            [self::class, self::MODULE_CAROUSELINNER_AUTHOREVENTS],
            [self::class, self::MODULE_CAROUSELINNER_TAGEVENTS],
        );
    }

    public function getLayoutGrid(array $module, array &$props)
    {
        switch ($module[1]) {
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

        return parent::getLayoutGrid($module, $props);
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_CAROUSELINNER_EVENTS:
            case self::MODULE_CAROUSELINNER_AUTHOREVENTS:
            case self::MODULE_CAROUSELINNER_TAGEVENTS:
                // Allow to override. Eg: TPP Debate needs a different format
                $layout = \PoP\Root\App::applyFilters(
                    'GD_EM_Module_Processor_CustomCarouselInners:layout', 
                    [GD_EM_Module_Processor_CustomPreviewPostLayouts::class, GD_EM_Module_Processor_CustomPreviewPostLayouts::MODULE_LAYOUT_PREVIEWPOST_EVENT_LIST], 
                    $module
                );
                $ret[] = $layout;
                break;
        }

        return $ret;
    }
}


