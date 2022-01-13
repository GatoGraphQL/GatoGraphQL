<?php
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoP\ComponentModel\Modules\ModuleUtils;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GetPoPDemo_Module_Processor_TopLevelCollapseComponents extends PoP_Module_Processor_CollapsePanelGroupComponentsBase
{
    public const MODULE_GETPOPDEMO_COLLAPSECOMPONENT_HOMETOP = 'getpopdemo-collapsecomponent-hometop';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_GETPOPDEMO_COLLAPSECOMPONENT_HOMETOP],
        );
    }

    public function getPanelSubmodules(array $module)
    {
        $ret = parent::getPanelSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_GETPOPDEMO_COLLAPSECOMPONENT_HOMETOP:
                if (defined('POP_EVENTSPROCESSORS_INITIALIZED')) {
                    $ret[] = [PoP_Events_Module_Processor_CustomSectionBlocks::class, PoP_Events_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_EVENTSCALENDAR_CALENDAR];
                    $ret[] = [GD_EM_Module_Processor_CustomScrollMapSectionBlocks::class, GD_EM_Module_Processor_CustomScrollMapSectionBlocks::MODULE_BLOCK_EVENTS_HORIZONTALSCROLLMAP];
                }
                break;
        }

        return $ret;
    }

    protected function lazyLoadInactivePanels(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_GETPOPDEMO_COLLAPSECOMPONENT_HOMETOP:
                return true;
        }

        return parent::lazyLoadInactivePanels($module, $props);
    }

    public function getPanelHeaderType(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_GETPOPDEMO_COLLAPSECOMPONENT_HOMETOP:
                return 'heading';
        }

        return parent::getPanelHeaderType($module);
    }

    public function closeParent(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_GETPOPDEMO_COLLAPSECOMPONENT_HOMETOP:
                return false;
        }

        return parent::closeParent($module);
    }

    public function getPanelTitle(array $module)
    {
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
        $placeholder =
        '<div class="media">'.
        '<div class="media-left">'.
        '<h2 class="media-heading"><i class="fa fa-fw fa-2x %1$s"></i></h2>'.
        '</div>'.
        '<div class="media-body">'.
        '<div class="pull-right"><i class="fa fa-fw fa-chevron-up collapse-arrow"></i></div>'.
        '<h3 class="media-heading">'.
         '%2$s'.
        '</h3>'.
        '%3$s'.
        '</div>'.
        '</div>';

        switch ($module[1]) {
            case self::MODULE_GETPOPDEMO_COLLAPSECOMPONENT_HOMETOP:
                $ret = array();
                if (defined('POP_EVENTSPROCESSORS_INITIALIZED')) {
                    $events_title = sprintf(
                        $placeholder,
                        'fa-calendar',
                        TranslationAPIFacade::getInstance()->__('Events Calendar', 'getpopdemo-processors'),
                        TranslationAPIFacade::getInstance()->__('Find out about upcoming happenings such as workshops, conferences, film festivals, and more.', 'getpopdemo-processors')
                    );
                    $map_title = sprintf(
                        $placeholder,
                        'fa-map-marker',
                        TranslationAPIFacade::getInstance()->__('Events Map', 'getpopdemo-processors'),
                        TranslationAPIFacade::getInstance()->__('Or, if you prefer, you can browse the events by their location.', 'getpopdemo-processors')
                    );
                    $ret[ModuleUtils::getModuleOutputName([PoP_Events_Module_Processor_CustomSectionBlocks::class, PoP_Events_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_EVENTSCALENDAR_CALENDAR])] = $events_title;
                    $ret[ModuleUtils::getModuleOutputName([GD_EM_Module_Processor_CustomScrollMapSectionBlocks::class, GD_EM_Module_Processor_CustomScrollMapSectionBlocks::MODULE_BLOCK_EVENTS_HORIZONTALSCROLLMAP])] = $map_title;
                }

                return $ret;
        }

        return parent::getPanelTitle($module);
    }

    public function getPaneltitleHtmltag(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_GETPOPDEMO_COLLAPSECOMPONENT_HOMETOP:
                return 'span';
        }

        return parent::getPaneltitleHtmltag($module);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_GETPOPDEMO_COLLAPSECOMPONENT_HOMETOP:
                $this->appendProp($module, $props, 'class', 'collapse-hometop');

                // Format
                $this->setProp([[PoP_Events_Module_Processor_CustomSectionBlocks::class, PoP_Events_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_EVENTSCALENDAR_CALENDAR]], $props, 'title-htmltag', 'h3');
                $this->setProp([[PoP_Events_Module_Processor_CustomSectionBlocks::class, PoP_Events_Module_Processor_CustomSectionBlocks::MODULE_BLOCK_EVENTSCALENDAR_CALENDAR]], $props, 'add-titlelink', true);
                $this->setProp([[GD_EM_Module_Processor_CustomScrollMapSectionBlocks::class, GD_EM_Module_Processor_CustomScrollMapSectionBlocks::MODULE_BLOCK_EVENTS_HORIZONTALSCROLLMAP]], $props, 'title-htmltag', 'h3');
                $this->setProp([[GD_EM_Module_Processor_CustomScrollMapSectionBlocks::class, GD_EM_Module_Processor_CustomScrollMapSectionBlocks::MODULE_BLOCK_EVENTS_HORIZONTALSCROLLMAP]], $props, 'add-titlelink', true);
                $this->setProp([[GD_EM_Module_Processor_CustomScrollMapSectionBlocks::class, GD_EM_Module_Processor_CustomScrollMapSectionBlocks::MODULE_BLOCK_EVENTS_HORIZONTALSCROLLMAP]], $props, 'collapsible', true);
                break;
        }

        parent::initModelProps($module, $props);
    }
}


