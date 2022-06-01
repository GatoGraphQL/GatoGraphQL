<?php
use PoPCMSSchema\Events\Facades\EventTypeAPIFacade;

class PoPTheme_EM_Processors_ContentHooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_Module_Processor_Contents:inner_component',
            $this->getContentInnerComponent(...),
            10,
            2
        );
    }

    public function getContentInnerComponent(\PoP\ComponentModel\Component\Component $inner, \PoP\ComponentModel\Component\Component $component): \PoP\ComponentModel\Component\Component
    {
        if (($component == [PoP_Module_Processor_Contents::class, PoP_Module_Processor_Contents::COMPONENT_CONTENT_SINGLE])) {
            $post_id = \PoP\Root\App::getState(['routing', 'queried-object-id']);
            $eventTypeAPI = EventTypeAPIFacade::getInstance();
            if ($eventTypeAPI->isEvent($post_id)) {
                $event = $eventTypeAPI->getEvent($post_id);
                if (defined('POP_EVENTLINKS_CAT_EVENTLINKS') && POP_EVENTLINKS_CAT_EVENTLINKS && eventHasCategory($event, POP_EVENTLINKS_CAT_EVENTLINKS)) {
                    return [PoP_ContentPostLinks_Module_Processor_SingleContentInners::class, PoP_ContentPostLinks_Module_Processor_SingleContentInners::COMPONENT_CONTENTINNER_LINKSINGLE];
                }
            }
        }

        return $inner;
    }
}

/**
 * Initialization
 */
new PoPTheme_EM_Processors_ContentHooks();
