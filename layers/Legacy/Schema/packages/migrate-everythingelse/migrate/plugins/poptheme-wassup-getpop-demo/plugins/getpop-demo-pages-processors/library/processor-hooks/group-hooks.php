<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class GetPoPDemo_Processors_GroupHooks
{
    public function __construct()
    {
        \PoP\Root\App::getHookManager()->addFilter(
            'PoP_Module_Processor_MainGroups:modules:home_tops',
            array($this, 'homeTopmodules')
        );
        \PoP\Root\App::getHookManager()->addFilter(
            'PoP_Module_Processor_CustomGroups:modules:author_widgetarea',
            array($this, 'getAuthortopWidgetSubmodules'),
            3
        );
        \PoP\Root\App::getHookManager()->addFilter(
            'PoP_Module_Processor_CustomGroups:modules:tag_widgetarea',
            array($this, 'getTagtopWidgetSubmodules'),
            3
        );
        \PoP\Root\App::getHookManager()->addAction(
            'PoP_Module_Processor_CustomGroups:modules:props',
            array($this, 'setModelProps'),
            10,
            3
        );
    }

    public function setModelProps(array $module, $props_in_array, $processor)
    {
        $props = &$props_in_array[0];
        switch ($module[1]) {
            case PoP_Module_Processor_CustomGroups::MODULE_GROUP_AUTHOR_WIDGETAREA:
            case PoP_Module_Processor_CustomGroups::MODULE_GROUP_TAG_WIDGETAREA:
                // Hide if block is empty
                foreach ($processor->getSubmodules($module) as $submodule) {
                    $processor->setProp([$submodule], $props, 'do-not-render-if-no-results', true);
                }
                break;
        }

        switch ($module[1]) {
            case PoP_Module_Processor_CustomGroups::MODULE_GROUP_AUTHOR_WIDGETAREA:
                // Format
                $processor->setProp([[GD_EM_Module_Processor_CustomScrollMapSectionBlocks::class, GD_EM_Module_Processor_CustomScrollMapSectionBlocks::MODULE_BLOCK_AUTHOREVENTS_HORIZONTALSCROLLMAP]], $props, 'title-htmltag', 'h3');
                $processor->setProp([[GD_EM_Module_Processor_CustomScrollMapSectionBlocks::class, GD_EM_Module_Processor_CustomScrollMapSectionBlocks::MODULE_BLOCK_AUTHOREVENTS_HORIZONTALSCROLLMAP]], $props, 'add-titlelink', true);
                $processor->setProp([[GD_EM_Module_Processor_CustomScrollMapSectionBlocks::class, GD_EM_Module_Processor_CustomScrollMapSectionBlocks::MODULE_BLOCK_AUTHOREVENTS_HORIZONTALSCROLLMAP]], $props, 'collapsible', true);
                break;

            case PoP_Module_Processor_CustomGroups::MODULE_GROUP_TAG_WIDGETAREA:
                $processor->setProp([[GD_EM_Module_Processor_CustomScrollMapSectionBlocks::class, GD_EM_Module_Processor_CustomScrollMapSectionBlocks::MODULE_BLOCK_TAGEVENTS_HORIZONTALSCROLLMAP]], $props, 'title-htmltag', 'h3');
                $processor->setProp([[GD_EM_Module_Processor_CustomScrollMapSectionBlocks::class, GD_EM_Module_Processor_CustomScrollMapSectionBlocks::MODULE_BLOCK_TAGEVENTS_HORIZONTALSCROLLMAP]], $props, 'add-titlelink', true);
                $processor->setProp([[GD_EM_Module_Processor_CustomScrollMapSectionBlocks::class, GD_EM_Module_Processor_CustomScrollMapSectionBlocks::MODULE_BLOCK_TAGEVENTS_HORIZONTALSCROLLMAP]], $props, 'collapsible', true);
                break;
        }
    }

    
    public function homeTopmodules($modules)
    {
        return array(
            [GetPoPDemo_Module_Processor_CustomGroups::class, GetPoPDemo_Module_Processor_CustomGroups::MODULE_GETPOPDEMO_GROUP_HOMETOP],
        );
    }

    public function getAuthortopWidgetSubmodules($modules)
    {

        // Add the Group which has the Featured widget
        if (defined('POP_EVENTSPROCESSORS_INITIALIZED')) {
            $modules[] = [GD_EM_Module_Processor_CustomScrollMapSectionBlocks::class, GD_EM_Module_Processor_CustomScrollMapSectionBlocks::MODULE_BLOCK_AUTHOREVENTS_HORIZONTALSCROLLMAP];
        }
        return $modules;
    }

    public function getTagtopWidgetSubmodules($modules)
    {

        // Add the Group which has the Featured widget
        if (defined('POP_EVENTSPROCESSORS_INITIALIZED')) {
            $modules[] = [GD_EM_Module_Processor_CustomScrollMapSectionBlocks::class, GD_EM_Module_Processor_CustomScrollMapSectionBlocks::MODULE_BLOCK_TAGEVENTS_HORIZONTALSCROLLMAP];
        }
        return $modules;
    }
}

/**
 * Initialization
 */
new GetPoPDemo_Processors_GroupHooks();
