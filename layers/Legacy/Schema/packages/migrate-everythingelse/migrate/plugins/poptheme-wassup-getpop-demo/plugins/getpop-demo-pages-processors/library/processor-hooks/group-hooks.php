<?php

class GetPoPDemo_Processors_GroupHooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_Module_Processor_MainGroups:components:home_tops',
            $this->homeTopmodules(...)
        );
        \PoP\Root\App::addFilter(
            'PoP_Module_Processor_CustomGroups:components:author_widgetarea',
            $this->getAuthortopWidgetSubmodules(...),
            3
        );
        \PoP\Root\App::addFilter(
            'PoP_Module_Processor_CustomGroups:components:tag_widgetarea',
            $this->getTagtopWidgetSubmodules(...),
            3
        );
        \PoP\Root\App::addAction(
            'PoP_Module_Processor_CustomGroups:components:props',
            $this->setModelProps(...),
            10,
            3
        );
    }

    public function setModelProps(array $component, $props_in_array, $processor)
    {
        $props = &$props_in_array[0];
        switch ($component[1]) {
            case PoP_Module_Processor_CustomGroups::COMPONENT_GROUP_AUTHOR_WIDGETAREA:
            case PoP_Module_Processor_CustomGroups::COMPONENT_GROUP_TAG_WIDGETAREA:
                // Hide if block is empty
                foreach ($processor->getSubcomponents($component) as $subComponent) {
                    $processor->setProp([$subComponent], $props, 'do-not-render-if-no-results', true);
                }
                break;
        }

        switch ($component[1]) {
            case PoP_Module_Processor_CustomGroups::COMPONENT_GROUP_AUTHOR_WIDGETAREA:
                // Format
                $processor->setProp([[GD_EM_Module_Processor_CustomScrollMapSectionBlocks::class, GD_EM_Module_Processor_CustomScrollMapSectionBlocks::COMPONENT_BLOCK_AUTHOREVENTS_HORIZONTALSCROLLMAP]], $props, 'title-htmltag', 'h3');
                $processor->setProp([[GD_EM_Module_Processor_CustomScrollMapSectionBlocks::class, GD_EM_Module_Processor_CustomScrollMapSectionBlocks::COMPONENT_BLOCK_AUTHOREVENTS_HORIZONTALSCROLLMAP]], $props, 'add-titlelink', true);
                $processor->setProp([[GD_EM_Module_Processor_CustomScrollMapSectionBlocks::class, GD_EM_Module_Processor_CustomScrollMapSectionBlocks::COMPONENT_BLOCK_AUTHOREVENTS_HORIZONTALSCROLLMAP]], $props, 'collapsible', true);
                break;

            case PoP_Module_Processor_CustomGroups::COMPONENT_GROUP_TAG_WIDGETAREA:
                $processor->setProp([[GD_EM_Module_Processor_CustomScrollMapSectionBlocks::class, GD_EM_Module_Processor_CustomScrollMapSectionBlocks::COMPONENT_BLOCK_TAGEVENTS_HORIZONTALSCROLLMAP]], $props, 'title-htmltag', 'h3');
                $processor->setProp([[GD_EM_Module_Processor_CustomScrollMapSectionBlocks::class, GD_EM_Module_Processor_CustomScrollMapSectionBlocks::COMPONENT_BLOCK_TAGEVENTS_HORIZONTALSCROLLMAP]], $props, 'add-titlelink', true);
                $processor->setProp([[GD_EM_Module_Processor_CustomScrollMapSectionBlocks::class, GD_EM_Module_Processor_CustomScrollMapSectionBlocks::COMPONENT_BLOCK_TAGEVENTS_HORIZONTALSCROLLMAP]], $props, 'collapsible', true);
                break;
        }
    }

    
    public function homeTopmodules($components)
    {
        return array(
            [GetPoPDemo_Module_Processor_CustomGroups::class, GetPoPDemo_Module_Processor_CustomGroups::COMPONENT_GETPOPDEMO_GROUP_HOMETOP],
        );
    }

    public function getAuthortopWidgetSubmodules($components)
    {

        // Add the Group which has the Featured widget
        if (defined('POP_EVENTSPROCESSORS_INITIALIZED')) {
            $components[] = [GD_EM_Module_Processor_CustomScrollMapSectionBlocks::class, GD_EM_Module_Processor_CustomScrollMapSectionBlocks::COMPONENT_BLOCK_AUTHOREVENTS_HORIZONTALSCROLLMAP];
        }
        return $components;
    }

    public function getTagtopWidgetSubmodules($components)
    {

        // Add the Group which has the Featured widget
        if (defined('POP_EVENTSPROCESSORS_INITIALIZED')) {
            $components[] = [GD_EM_Module_Processor_CustomScrollMapSectionBlocks::class, GD_EM_Module_Processor_CustomScrollMapSectionBlocks::COMPONENT_BLOCK_TAGEVENTS_HORIZONTALSCROLLMAP];
        }
        return $components;
    }
}

/**
 * Initialization
 */
new GetPoPDemo_Processors_GroupHooks();
