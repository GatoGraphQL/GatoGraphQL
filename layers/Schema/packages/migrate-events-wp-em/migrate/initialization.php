<?php
class EM_PoPEvents_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('events-manager-pop-events', false, dirname(plugin_basename(__FILE__)).'/languages');

        include_once 'library/load.php';
        include_once 'plugins/load.php';
    }
}
