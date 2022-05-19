<?php
namespace PoP\Application;

class ComponentRoutingProcessorManager extends \PoP\ComponentModel\ComponentRouting\ComponentRoutingProcessorManager
{
    public function getDefaultGroup(): string
    {
        return POP_PAGECOMPONENTGROUPPLACEHOLDER_MAINCONTENTCOMPONENT;
    }
}

// Quick fix until migrating to Symfony services.yaml, then no need for this horrible shit anymore
add_action('init', function() {
	/**
	 * Initialization
	 */
	new ComponentRoutingProcessorManager();
}, 200);