<?php

class PoPWebPlatform_ModuleManager_Utils
{
    public static function addJsmethod(&$ret, $method, $group = '', $unshift = false, $priority = null)
    {
        $priority = $priority ?? POP_PROGRESSIVEBOOTING_NONCRITICAL;
        $group = $group ? $group : GD_JSMETHOD_GROUP_MAIN;

        // $ret[$priority] = $ret[$priority] ?? array();
        $ret[$priority][$group] = $ret[$priority][$group] ?? array();

        if ($unshift) {
            array_unshift($ret[$priority][$group], $method);
        } else {
            $ret[$priority][$group][] = $method;
        }
    }
    public static function removeJsmethod(&$ret, $method, $group = GD_JSMETHOD_GROUP_MAIN)
    {
        if ($ret[$group]) {

            // Remove from both 'critical' and 'noncritical' priorities
            $priorities = array(
                POP_PROGRESSIVEBOOTING_CRITICAL,
                POP_PROGRESSIVEBOOTING_NONCRITICAL,
            );
            foreach ($priorities as $priority) {
                if ($ret[$group][$priority]) {
                    $pos = array_search($method, $ret[$group][$priority]);
                    if ($pos !== false) {
                        array_splice($ret[$group][$priority], $pos, 1);
                    }
                }
            }
        }
    }
}
