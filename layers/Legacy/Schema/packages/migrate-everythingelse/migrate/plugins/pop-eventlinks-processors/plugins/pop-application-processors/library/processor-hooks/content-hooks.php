<?php
use PoP\ComponentModel\State\ApplicationState;
use PoPCMSSchema\Events\Facades\EventTypeAPIFacade;

class PoPTheme_EM_Processors_ContentHooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_Module_Processor_Contents:inner_module',
            $this->contentInner(...),
            10,
            2
        );
    }

    public function contentInner($inner, array $module)
    {
        if (($module == [PoP_Module_Processor_Contents::class, PoP_Module_Processor_Contents::MODULE_CONTENT_SINGLE])) {
            $post_id = \PoP\Root\App::getState(['routing', 'queried-object-id']);
            $eventTypeAPI = EventTypeAPIFacade::getInstance();
            if ($eventTypeAPI->isEvent($post_id)) {
                $event = $eventTypeAPI->getEvent($post_id);
                if (defined('POP_EVENTLINKS_CAT_EVENTLINKS') && POP_EVENTLINKS_CAT_EVENTLINKS && eventHasCategory($event, POP_EVENTLINKS_CAT_EVENTLINKS)) {
                    return [PoP_ContentPostLinks_Module_Processor_SingleContentInners::class, PoP_ContentPostLinks_Module_Processor_SingleContentInners::MODULE_CONTENTINNER_LINKSINGLE];
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
