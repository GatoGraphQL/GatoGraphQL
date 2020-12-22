<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoPSchema\Events\Facades\EventTypeAPIFacade;
use PoPSchema\CustomPosts\Routing\RouteNatures as CustomPostRouteNatures;

class PoP_Events_Engine_Hooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addAction(
            'augmentVarsProperties',
            [$this, 'augmentVarsProperties'],
            10,
            1
        );
    }

    /**
     * @param array<array> $vars_in_array
     */
    public function augmentVarsProperties(array $vars_in_array): void
    {

        // Set additional properties based on the nature: the global $post, $author, or $queried_object
        $vars = &$vars_in_array[0];
        $nature = $vars['nature'];

        // Attributes needed to match the RouteModuleProcessor vars conditions
        if ($nature == CustomPostRouteNatures::CUSTOMPOST) {
            $eventTypeAPI = EventTypeAPIFacade::getInstance();
            $customPostType = $vars['routing-state']['queried-object-post-type'];
            if ($customPostType == $eventTypeAPI->getEventCustomPostType()) {
                $post_id = $vars['routing-state']['queried-object-id'];
                if ($eventTypeAPI->isFutureEvent($post_id)) {
                    $vars['routing-state']['queried-object-is-future-event'] = true;
                } elseif ($eventTypeAPI->isCurrentEvent($post_id)) {
                    $vars['routing-state']['queried-object-is-current-event'] = true;
                } elseif ($eventTypeAPI->isPastEvent($post_id)) {
                    $vars['routing-state']['queried-object-is-past-event'] = true;
                }
            }
        }
    }
}

/**
 * Initialization
 */
new PoP_Events_Engine_Hooks();
