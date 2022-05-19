<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GetPoPDemo_Module_Processor_TopLevelCollapseComponents extends PoP_Module_Processor_CollapsePanelGroupComponentsBase
{
    public final const COMPONENT_GETPOPDEMO_COLLAPSECOMPONENT_HOMETOP = 'getpopdemo-collapsecomponent-hometop';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_GETPOPDEMO_COLLAPSECOMPONENT_HOMETOP],
        );
    }

    public function getPanelSubcomponents(array $component)
    {
        $ret = parent::getPanelSubcomponents($component);

        switch ($component[1]) {
            case self::COMPONENT_GETPOPDEMO_COLLAPSECOMPONENT_HOMETOP:
                if (defined('POP_EVENTSPROCESSORS_INITIALIZED')) {
                    $ret[] = [PoP_Events_Module_Processor_CustomSectionBlocks::class, PoP_Events_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_EVENTSCALENDAR_CALENDAR];
                    $ret[] = [GD_EM_Module_Processor_CustomScrollMapSectionBlocks::class, GD_EM_Module_Processor_CustomScrollMapSectionBlocks::COMPONENT_BLOCK_EVENTS_HORIZONTALSCROLLMAP];
                }
                break;
        }

        return $ret;
    }

    protected function lazyLoadInactivePanels(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_GETPOPDEMO_COLLAPSECOMPONENT_HOMETOP:
                return true;
        }

        return parent::lazyLoadInactivePanels($component, $props);
    }

    public function getPanelHeaderType(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_GETPOPDEMO_COLLAPSECOMPONENT_HOMETOP:
                return 'heading';
        }

        return parent::getPanelHeaderType($component);
    }

    public function closeParent(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_GETPOPDEMO_COLLAPSECOMPONENT_HOMETOP:
                return false;
        }

        return parent::closeParent($component);
    }

    public function getPanelTitle(array $component)
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
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

        switch ($component[1]) {
            case self::COMPONENT_GETPOPDEMO_COLLAPSECOMPONENT_HOMETOP:
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
                    $ret[\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName([PoP_Events_Module_Processor_CustomSectionBlocks::class, PoP_Events_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_EVENTSCALENDAR_CALENDAR])] = $events_title;
                    $ret[\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName([GD_EM_Module_Processor_CustomScrollMapSectionBlocks::class, GD_EM_Module_Processor_CustomScrollMapSectionBlocks::COMPONENT_BLOCK_EVENTS_HORIZONTALSCROLLMAP])] = $map_title;
                }

                return $ret;
        }

        return parent::getPanelTitle($component);
    }

    public function getPaneltitleHtmltag(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_GETPOPDEMO_COLLAPSECOMPONENT_HOMETOP:
                return 'span';
        }

        return parent::getPaneltitleHtmltag($component);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_GETPOPDEMO_COLLAPSECOMPONENT_HOMETOP:
                $this->appendProp($component, $props, 'class', 'collapse-hometop');

                // Format
                $this->setProp([[PoP_Events_Module_Processor_CustomSectionBlocks::class, PoP_Events_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_EVENTSCALENDAR_CALENDAR]], $props, 'title-htmltag', 'h3');
                $this->setProp([[PoP_Events_Module_Processor_CustomSectionBlocks::class, PoP_Events_Module_Processor_CustomSectionBlocks::COMPONENT_BLOCK_EVENTSCALENDAR_CALENDAR]], $props, 'add-titlelink', true);
                $this->setProp([[GD_EM_Module_Processor_CustomScrollMapSectionBlocks::class, GD_EM_Module_Processor_CustomScrollMapSectionBlocks::COMPONENT_BLOCK_EVENTS_HORIZONTALSCROLLMAP]], $props, 'title-htmltag', 'h3');
                $this->setProp([[GD_EM_Module_Processor_CustomScrollMapSectionBlocks::class, GD_EM_Module_Processor_CustomScrollMapSectionBlocks::COMPONENT_BLOCK_EVENTS_HORIZONTALSCROLLMAP]], $props, 'add-titlelink', true);
                $this->setProp([[GD_EM_Module_Processor_CustomScrollMapSectionBlocks::class, GD_EM_Module_Processor_CustomScrollMapSectionBlocks::COMPONENT_BLOCK_EVENTS_HORIZONTALSCROLLMAP]], $props, 'collapsible', true);
                break;
        }

        parent::initModelProps($component, $props);
    }
}


