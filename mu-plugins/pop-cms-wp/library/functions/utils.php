<?php

// Taken from https://wordpress.stackexchange.com/questions/4041/how-to-activate-plugins-via-code
function runActivatePlugin($plugin)
{
    $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
    $current = $cmsapi->getOption('active_plugins');
    $plugin = plugin_basename(trim($plugin));

    if (!in_array($plugin, $current)) {
        $current[] = $plugin;
        sort($current);
        \PoP\CMS\HooksAPI_Factory::getInstance()->doAction('activate_plugin', trim($plugin));
        update_option('active_plugins', $current);
        \PoP\CMS\HooksAPI_Factory::getInstance()->doAction('activate_' . trim($plugin));
        \PoP\CMS\HooksAPI_Factory::getInstance()->doAction('activated_plugin', trim($plugin));
    }

    return null;
}
