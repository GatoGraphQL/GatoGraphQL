<?php
namespace PoPSchema\UserRoles\WP;
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class FunctionAPIHooks {

	public function __construct() {
	
		HooksAPIFacade::getInstance()->addFilter(
		    'CMSAPI:users:query',
		    [$this, 'convertUsersQuery'],
		    10,
		    2
		);
	}

	public function convertUsersQuery($query, array $options)
    {
        if (isset($query['role'])) {
            // Same param name, so do nothing
        }
        if (isset($query['role-in']) ){ 
            
            $query['role__in'] = $query['role-in'];
            unset($query['role-in']);
        }
        if (isset($query['role-not-in']) ){ 
            
            $query['role__not_in'] = $query['role-not-in'];
            unset($query['role-not-in']);
        }
        
        return $query;
    }
}

/**
 * Initialize
 */
new FunctionAPIHooks();
