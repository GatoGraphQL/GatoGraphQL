<?php
use PoP\Hooks\Facades\HooksAPIFacade;

class PoP_Application_ApplicationState
{
    /**
     * @todo Migrate to AppStateProvider
     * @param array<array> $vars_in_array
     */
    public static function addVars(array $vars_in_array): void
    {
        $vars = &$vars_in_array[0];
        if ($timestamp = $_REQUEST[GD_URLPARAM_TIMESTAMP] ?? null) {
            \PoP\Root\App::getState('timestamp') = (int) $timestamp;
        }

        \PoP\Root\App::getState('loading-latest') =
        	in_array(GD_URLPARAM_ACTION_LOADLATEST, \PoP\Root\App::getState('actions'))  &&
	        // Also make sure a timestamp was passed along
        	isset(\PoP\Root\App::getState('timestamp')) &&
        	// Only for allowed routes, such as notifications (avoid calling the homepage with "loading-latest" or the amount of retrieved data can bring the server down)
        	in_array(
        		\PoP\Root\App::getState('route'),
        		HooksAPIFacade::getInstance()->applyFilters(
        			'loadingLatestRoutes',
        			[]
        		)
        	)
        ;
    }
}

/**
 * Initialization
 */
HooksAPIFacade::getInstance()->addAction('ApplicationState:addVars', array(PoP_Application_ApplicationState::class, 'addVars'), 10, 1);
