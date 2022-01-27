<?php

class PoPTheme_Wassup_ServerUtils
{
    public static function enableFlushRules()
    {
        // By default do NOT allow to flush rules, because:
        // It will generate a HUGE sql query, whose execution takes the latency way up, and it will consume a HUGE bandwidth between EC2 and the DB, costing real $$$
        return getenv('ENABLE_FLUSH_RULES') !== false ? strtolower(getenv('ENABLE_FLUSH_RULES')) === "true" : false;
    }

    public static function disablePreloadingPages()
    {
        return getenv('DISABLE_PRELOADING_PAGES') !== false ? strtolower(getenv('DISABLE_PRELOADING_PAGES')) === "true" : false;
    }
}
