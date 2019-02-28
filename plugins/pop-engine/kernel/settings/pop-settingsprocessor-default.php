<?php
namespace PoP\Engine\Settings;

abstract class DefaultSettingsProcessorBase extends SettingsProcessorBase
{
    public function init()
    {
        parent::init();

        SettingsProcessorManager_Factory::getInstance()->setDefault($this);
    }
}
