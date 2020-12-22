<?php
use PoP\Hooks\Facades\HooksAPIFacade;
/**
 * Install extra capabilities for the roles
 */
                        
HooksAPIFacade::getInstance()->addFilter('PoP_UserPlatform_Installation:install:capabilities', 'emPopEventsInstallExtraCapabilities');
function emPopEventsInstallExtraCapabilities($capabilities)
{
    return array_merge(
        $capabilities,
        array(
            'publish_events' => true,
            'delete_events' => true,
            'edit_events' => true,
            'read_private_events' => true,
            'delete_recurring_events' => true,
            'edit_recurring_events' => true,
            // 'edit_locations' => true,
            // 'read_private_locations' => true,
            'read_others_locations' => true,
            'manage_bookings' => true,
            'upload_event_images' => true,
            // 'publish_locations' => true // Needed to allow users to create Locations for Actions and be published immediately
        )
    );
}
