<?php

class PoP_Application_ApplicationState
{
    /**
     * @todo Migrate to AppStateProvider
     * @param array<array> $vars_in_array
     */
    public static function addVars(array $vars_in_array): void
    {
        $vars = &$vars_in_array[0];
        if ($timestamp = $_GET[GD_URLPARAM_TIMESTAMP] ?? null) {
            $vars['timestamp'] = (int) $timestamp;
        }

        $vars['loading-latest'] =
        	in_array(GD_URLPARAM_ACTION_LOADLATEST, $vars['actions'])  &&
	        // Also make sure a timestamp was passed along
        	isset($vars['timestamp']) &&
        	// Only for allowed routes, such as notifications (avoid calling the homepage with "loading-latest" or the amount of retrieved data can bring the server down)
        	in_array(
        		$vars['route'],
        		\PoP\Root\App::applyFilters(
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
\PoP\Root\App::addAction('ApplicationState:addVars', array(PoP_Application_ApplicationState::class, 'addVars'), 10, 1);
