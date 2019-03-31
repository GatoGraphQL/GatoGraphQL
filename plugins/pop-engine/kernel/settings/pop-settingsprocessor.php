<?php
namespace PoP\Engine\Settings;

abstract class SettingsProcessorBase
{
    public function __construct()
    {

        // Comment Leo 30/09/2017: Important: do it at the very end, to make sure that
        // all constants have been set by then (otherwise, in file settingsprocessor.pht
        // it may add the configuration under page "POP_CATEGORYPOSTS_PAGE_CATEGORYPOSTS01",
        // it is not treated as false if the constant has not been defined)
        \PoP\CMS\HooksAPI_Factory::getInstance()->addAction(
            'popcms:init', 
            array($this, 'init'), 
            PHP_INT_MAX
        );
    }

    public function init()
    {
        SettingsProcessorManager_Factory::getInstance()->add($this);
    }

    abstract public function pagesToProcess();

    // function getCheckpointConfiguration() {
    public function getCheckpoints()
    {
        return array();
    }

    public function is_functional()
    {
        return false;
    }

    public function isForInternalUse()
    {
        return false;
    }

    public function needsTargetId()
    {
        return false;
    }

    public function getRedirectUrl()
    {
        return null;
    }
}
