<?php

// Taken from https://wordpress.stackexchange.com/questions/4041/how-to-activate-plugins-via-code
function runActivatePlugin($plugin)
{
    $current = getOption('active_plugins');
    $plugin = plugin_basename(trim($plugin));

    if (!in_array($plugin, $current)) {
        $current[] = $plugin;
        sort($current);
        do_action('activate_plugin', trim($plugin));
        update_option('active_plugins', $current);
        do_action('activate_' . trim($plugin));
        do_action('activated_plugin', trim($plugin));
    }

    return null;
}
