<?php
namespace PoPSchema\UserState\Settings\Impl;

class DefaultSettingsProcessor extends \PoPSchema\UserState\Settings\DefaultSettingsProcessorBase
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
