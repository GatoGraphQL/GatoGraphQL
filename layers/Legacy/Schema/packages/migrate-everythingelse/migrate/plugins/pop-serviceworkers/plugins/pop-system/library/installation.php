<?php
class PoP_ServiceWorkers_Installation
{
    public function __construct()
    {
        \PoP\Root\App::addAction('PoP:system-generate', array($this, 'systemGenerate'));
    }

    public function systemGenerate()
    {

        // Do not install immediately, but do it only at the end of everything, so that the precache list
        // can have added all the resources from the footer too
        // Because it's added in 'wp_footer', it never gets called if doing output=json, which is alright
        global $pop_serviceworkers_manager;
        \PoP\Root\App::addAction(
            'popcms:footer', 
            array($pop_serviceworkers_manager, 'generateFiles'), 
            10000
        );
    }
}

/**
 * Initialization
 */
new PoP_ServiceWorkers_Installation();
