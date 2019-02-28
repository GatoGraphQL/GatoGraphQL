<?php
namespace PoP\Engine\Settings\Impl;

class DefaultSettingsProcessor extends \PoP\Engine\Settings\DefaultSettingsProcessorBase
{
    public function pagesToProcess()
    {
        return array();
    }
}

/**
 * Initialization
 */
new DefaultSettingsProcessor();
