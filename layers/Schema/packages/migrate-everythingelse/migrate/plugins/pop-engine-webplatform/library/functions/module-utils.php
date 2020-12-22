<?php
use PoP\Hooks\Facades\HooksAPIFacade;

class PoP_WebPlatformEngine_Module_Utils
{
    public static function getMergeClass($inner_modules)
    {
        if (!is_array($inner_modules)) {
            $inner_modules = array($inner_modules);
        }

        return POP_CLASSPREFIX_MERGE.implode(' '.POP_CLASSPREFIX_MERGE, $inner_modules);
    }
}
