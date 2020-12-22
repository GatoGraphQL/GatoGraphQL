<?php
class EM_PoP_Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('events-manager-pop', false, dirname(plugin_basename(__FILE__)).'/languages');

        include_once 'library/load.php';
    }
}
