<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoPSchema\Events\Facades\EventTypeAPIFacade;
use PoP\ComponentModel\State\ApplicationState;

class PoPTheme_EM_Processors_ContentHooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addFilter(
            'PoP_Module_Processor_Contents:inner_module',
            array($this, 'contentInner'),
            10,
            2
        );
    }

    public function contentInner($inner, array $module)
    {
        if (($module == [PoP_Module_Processor_Contents::class, PoP_Module_Processor_Contents::MODULE_CONTENT_SINGLE])) {
            $vars = ApplicationState::getVars();
            $post_id = $vars['routing-state']['queried-object-id'];
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
