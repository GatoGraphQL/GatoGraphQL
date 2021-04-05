<?php
namespace PoPSchema\Categories\WP;

class Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-categories-wp', false, dirname(plugin_basename(__FILE__)).'/languages');
    }
}
