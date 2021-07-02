<?php
if (! defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

class AAL_PoP_Settings
{
    public $slug = 'activity-log-settings';
    
    public function get_option($key = '')
    {
        return false;
    }
    
    /**
     * Returns all options
     *
     * @since  2.0.7
     * @return array
     */
    public function get_options()
    {
        return array();
    }
    
    public function slug()
    {
        return $this->slug;
    }
}
