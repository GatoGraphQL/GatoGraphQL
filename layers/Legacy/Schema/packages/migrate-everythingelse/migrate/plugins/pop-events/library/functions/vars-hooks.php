<?php
use PoP\ComponentModel\State\ApplicationState;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPSchema\Events\Facades\EventTypeAPIFacade;

// Event and Past Event have different configurations, so we must differentiate among them
\PoP\Root\App::getHookManager()->addFilter(\PoP\ComponentModel\ModelInstance\ModelInstance::HOOK_COMPONENTS_RESULT, 'popEmModuleInstanceComponents');
function popEmModuleInstanceComponents($components)
{
    // Add source param for Organizations: view their profile as Community or Organization
    if (\PoP\Root\App::getState(['routing', 'is-custompost'])) {
        $post_id = \PoP\Root\App::getState(['routing', 'queried-object-id']);
        $eventTypeAPI = EventTypeAPIFacade::getInstance();
        if ($eventTypeAPI->isEvent($post_id)) {
            $components[] = TranslationAPIFacade::getInstance()->__('event scope:', 'pop-events').($eventTypeAPI->isFutureEvent($post_id) ? 'future' : ($eventTypeAPI->isCurrentEvent($post_id) ? 'current' : 'past'));
        }
    }

    return $components;
}
