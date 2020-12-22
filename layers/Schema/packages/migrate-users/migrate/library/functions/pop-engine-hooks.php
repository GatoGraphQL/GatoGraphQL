<?php
namespace PoPSchema\Users\WP;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoPSchema\Users\Routing\RouteNatures;

class Engine_Hooks
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
        $vars['routing-state']['is-user'] = $nature == RouteNatures::USER;
    }
}

/**
 * Initialization
 */
new Engine_Hooks();
