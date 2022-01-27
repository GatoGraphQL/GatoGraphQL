<?php
class PoPTheme_Wassup_Installation
{
    public function __construct()
    {
        \PoP\Root\App::addAction('PoP:system-build', array($this, 'changeAuthorFlushrules'));
        \PoP\Root\App::addAction('PoP:system-build', array($this, 'updateImageDefaultSize'));
    }

    /**
     * Set default thumb size from 'medium' to 'large'
     */
    public function updateImageDefaultSize()
    {
        update_option('image_default_size', 'large');
    }

    public function changeAuthorFlushrules()
    {

        // Comment Leo 05/05/2016: Executing flush_rules() is SO expensive, that we'd rather not do it unless really really have to
        // It will generate a HUGE sql query, whose execution takes the latency way up, and it will consume a HUGE bandwidth between EC2 and the DB, costing real $$$
        if (PoPTheme_Wassup_ServerUtils::enableFlushRules()) {
            changeAuthorPermalinks();

            global $wp_rewrite;
            $wp_rewrite->flush_rules();
        }
    }
}

/**
 * Initialization
 */
new PoPTheme_Wassup_Installation();
