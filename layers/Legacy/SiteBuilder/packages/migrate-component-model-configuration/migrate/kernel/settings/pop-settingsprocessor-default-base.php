<?php
namespace PoP\ComponentModel\Settings;

abstract class DefaultSettingsProcessorBase extends SettingsProcessorBase
{
    public function init()
    {
        parent::init();

        SettingsProcessorManagerFactory::getInstance()->setDefault($this);
    }
}
