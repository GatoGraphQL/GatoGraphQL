<?php

use PoP\ComponentModel\State\ApplicationState;

class GD_DataLoad_QueryInputOutputHandler_LatestNotificationList extends GD_DataLoad_QueryInputOutputHandler_NotificationList
{
    public function getHistTime(&$query_args)
    {
        if (\PoP\Root\App::getState('is-user-logged-in')) {
            // Since the last time the user logged in
            $lastaccess = \PoPCMSSchema\UserMeta\Utils::getUserMeta(\PoP\Root\App::getState('current-user-id'), POP_METAKEY_USER_LASTACCESS, true);
            
            // Alert the user about all past notifications
            // Newly created user accounts, there's still no lastaccess, so just give a "1" to make the comparison, it will return all results
            if (!$lastaccess) {
                return 1;
            }
            return $lastaccess;
        }
    
        return parent::getHistTime($query_args);
    }

    public function getHistTimeCompare(&$query_args)
    {
        return '>';
    }

    /**
     * @param array<string,mixed> $query_args
     */
    public function prepareQueryArgs(array &$query_args): void
    {
        parent::prepareQueryArgs($query_args);

        // Bring all results
        $query_args['pagenumber'] = 1;
        $query_args['limit'] = -1;
    }
}
    
/**
 * Initialize
 */
new GD_DataLoad_QueryInputOutputHandler_LatestNotificationList();
