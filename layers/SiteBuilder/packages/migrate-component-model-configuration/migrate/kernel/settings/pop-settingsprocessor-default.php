<?php
namespace PoP\Engine\Settings\Impl;

class DefaultSettingsProcessor extends \PoP\ComponentModel\Settings\DefaultSettingsProcessorBase
{
    public function routesToProcess()
    {
        return array();
    }
}

/**
 * Initialization
 */
new DefaultSettingsProcessor();
