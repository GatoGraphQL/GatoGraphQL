<?php
use PoP\ComponentModel\State\ApplicationState;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\Events\Facades\EventTypeAPIFacade;

// Event and Past Event have different configurations, so we must differentiate among them
HooksAPIFacade::getInstance()->addFilter(\PoP\ComponentModel\ModelInstance\ModelInstance::HOOK_COMPONENTS_RESULT, 'popEmModuleInstanceComponents');
function popEmModuleInstanceComponents($components)
{
    // Add source param for Organizations: view their profile as Community or Organization
    $vars = ApplicationState::getVars();
    if ($vars['routing']['is-custompost']) {
        $post_id = $vars['routing']['queried-object-id'];
        $eventTypeAPI = EventTypeAPIFacade::getInstance();
        if ($eventTypeAPI->isEvent($post_id)) {
            $components[] = TranslationAPIFacade::getInstance()->__('event scope:', 'pop-events').($eventTypeAPI->isFutureEvent($post_id) ? 'future' : ($eventTypeAPI->isCurrentEvent($post_id) ? 'current' : 'past'));
        }
    }

    return $components;
}
