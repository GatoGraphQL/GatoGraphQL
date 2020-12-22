<?php
use PoP\Hooks\Facades\HooksAPIFacade;
/**
 * Install extra capabilities for the roles
 */
                        
HooksAPIFacade::getInstance()->addFilter('PoP_UserPlatform_Installation:install:capabilities', 'emPopLocationsInstallExtraCapabilities');
function emPopLocationsInstallExtraCapabilities($capabilities)
{
    return array_merge(
        $capabilities,
        array(
            'edit_locations' => true,
            'read_private_locations' => true,
            'read_others_locations' => true,
            'publish_locations' => true // Needed to allow users to create Locations for Actions and be published immediately
        )
    );
}
