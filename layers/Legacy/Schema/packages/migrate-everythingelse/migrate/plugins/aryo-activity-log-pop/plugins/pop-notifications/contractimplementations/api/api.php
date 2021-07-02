<?php

class PoP_AAL_Notifications_FunctionsAPI extends PoP_Notifications_FunctionsAPI_Base implements PoP_Notifications_FunctionsAPI
{
    public function insertLog($args)
    {

        // // Normalize the args
        // $args = PoP_Notifications_API::convertArgs($args);

        // Swap the DB table to the one for the notifications
        AAL_PoP_AdaptationUtils::swapDbTable();
        
        // Let AAL to operate its functions on the PoP Notifications DB
        aal_insert_log($args);
        
        // Restore to the original AAL DB table
        AAL_PoP_AdaptationUtils::restoreDbTable();
    }
}

/**
 * Initialize
 */
new PoP_AAL_Notifications_FunctionsAPI();
