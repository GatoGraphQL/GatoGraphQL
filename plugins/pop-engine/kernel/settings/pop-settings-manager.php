<?php
namespace PoP\Engine\Settings;

class SettingsManager
{
    public function __construct()
    {
        SettingsManager_Factory::setInstance($this);
    }

    // function getCheckpointConfiguration($page_id = null) {
    public function getCheckpoints($page_id = null)
    {
        $vars = \PoP\Engine\Engine_Vars::getVars();
        if (!$page_id) {
            $page_id = \PoP\Engine\Utils::getHierarchyPageId();
        }

        $processor = SettingsProcessorManager_Factory::getInstance()->getProcessor($page_id/*, $hierarchy*/);
        // $checkpoints = $processor->getCheckpointConfiguration();
        $checkpoints = $processor->getCheckpoints();
        if ($checkpoints[$page_id]) {
            return $checkpoints[$page_id];
        }

        // return array(
        //     'checkpoints' => array(),
        //     // 'type' => false
        // );
        return array();
    }

    public function is_functional($page_id = null)
    {
        $vars = \PoP\Engine\Engine_Vars::getVars();
        if (!$page_id) {
            $page_id = \PoP\Engine\Utils::getHierarchyPageId();
        }

        $processor = SettingsProcessorManager_Factory::getInstance()->getProcessor($page_id);

        // If we get an array, then it defines the value on a page by page basis
        // Otherwise, it's true/false, just return it
        $functional = $processor->is_functional();
        if (is_array($functional)) {
            return $functional[$page_id];
        }

        return $functional;
    }

    public function isForInternalUse($page_id = null)
    {
        $vars = \PoP\Engine\Engine_Vars::getVars();
        if (!$page_id) {
            $page_id = \PoP\Engine\Utils::getHierarchyPageId();
        }

        $processor = SettingsProcessorManager_Factory::getInstance()->getProcessor($page_id);

        // If we get an array, then it defines the value on a page by page basis
        // Otherwise, it's true/false, just return it
        $internal = $processor->isForInternalUse();
        if (is_array($internal)) {
            return $internal[$page_id];
        }

        return $internal;
    }

    public function needsTargetId($page_id = null)
    {
        $vars = \PoP\Engine\Engine_Vars::getVars();
        if (!$page_id) {
            $page_id = \PoP\Engine\Utils::getHierarchyPageId();
        }
        
        $processor = SettingsProcessorManager_Factory::getInstance()->getProcessor($page_id);

        // If we get an array, then it defines the value on a page by page basis
        // Otherwise, it's true/false, just return it
        $targetids = $processor->needsTargetId();
        if (is_array($targetids)) {
            return $targetids[$page_id];
        }

        return $targetids;
    }

    public function getRedirectUrl($page_id = null)
    {
        $vars = \PoP\Engine\Engine_Vars::getVars();
        if (!$page_id) {
            $page_id = \PoP\Engine\Utils::getHierarchyPageId();
        }
        
        $processor = SettingsProcessorManager_Factory::getInstance()->getProcessor($page_id);

        // If we get an array, then it defines the value on a page by page basis
        // Otherwise, it's true/false, just return it
        $redirect_urls = $processor->getRedirectUrl();
        if (is_array($redirect_urls)) {
            return $redirect_urls[$page_id];
        }

        return $redirect_urls;
    }
}

/**
 * Initialization
 */
new SettingsManager();
