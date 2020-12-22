<?php
namespace PoPSchema\Posts\WP;

class Initialization
{
    public function initialize()
    {
        load_plugin_textdomain('pop-posts-wp', false, dirname(plugin_basename(__FILE__)).'/languages');
    }
}
