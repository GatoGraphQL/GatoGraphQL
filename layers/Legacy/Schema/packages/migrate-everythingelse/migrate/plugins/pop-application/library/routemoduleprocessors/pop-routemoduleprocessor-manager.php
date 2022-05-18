<?php
namespace PoP\Application;

class RouteModuleProcessorManager extends \PoP\ComponentModel\ComponentRouting\RouteModuleProcessorManager
{
    public function getDefaultGroup(): string
    {
        return POP_PAGEMODULEGROUPPLACEHOLDER_MAINCONTENTMODULE;
    }
}

// Quick fix until migrating to Symfony services.yaml, then no need for this horrible shit anymore
add_action('init', function() {
	/**
	 * Initialization
	 */
	new RouteModuleProcessorManager();
}, 200);