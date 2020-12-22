<?php
class EM_PoPLocations_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('events-manager-pop-locations', false, dirname(plugin_basename(__FILE__)).'/languages');

        include_once 'library/load.php';
        include_once 'plugins/load.php';
    }
}
