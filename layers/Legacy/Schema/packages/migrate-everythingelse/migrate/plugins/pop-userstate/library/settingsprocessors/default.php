<?php
namespace PoPCMSSchema\UserState\Settings\Impl;

class DefaultSettingsProcessor extends \PoPCMSSchema\UserState\Settings\DefaultSettingsProcessorBase
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
