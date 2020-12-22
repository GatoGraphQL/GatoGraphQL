<?php
use PoP\Hooks\Facades\HooksAPIFacade;

class EM_PoPLocations_DataloaderHooks
{
    public function __construct()
    {

        // Comment Leo 02/05/2018: commented because it destroys everything: it makes the locations appear repeated
        // HooksAPIFacade::getInstance()->addAction(
        //     'LocationTypeDataLoader:executeQueryIds:before',
        //     array($this, 'addFilter')
        // );
        // HooksAPIFacade::getInstance()->addAction(
        //     'LocationTypeDataLoader:executeQueryIds:after',
        //     array($this, 'removeFilter')
        // );
    }

    public function addFilter()
    {

        // Add filter to only bring the ids and nothing else
        HooksAPIFacade::getInstance()->addFilter(
            'em_locations_get_sql',
            array($this, 'emLocationsGetSql')
        );
    }

    public function removeFilter()
    {
        HooksAPIFacade::getInstance()->removeFilter(
            'em_locations_get_sql',
            array($this, 'emLocationsGetSql')
        );
    }

    public function emLocationsGetSql($sql)
    {

        // Modify the $sql to bring only the ids field
        $parts = explode(' FROM ', $sql);

        // Copied from EM_Events::get() (em-events.php)
        $locations_table = EM_LOCATIONS_TABLE;
        $post_id_field = $locations_table.'.post_id';

        $sql = "SELECT " . $post_id_field . " FROM " . $parts[1];

        return $sql;
    }
}

/**
 * Initialization
 */
new EM_PoPLocations_DataloaderHooks();
