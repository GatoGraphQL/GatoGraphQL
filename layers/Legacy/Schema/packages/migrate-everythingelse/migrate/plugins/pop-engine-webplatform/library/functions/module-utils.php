<?php

class PoP_WebPlatformEngine_Module_Utils
{
    public static function getMergeClass($inner_components)
    {
        if (!is_array($inner_components)) {
            $inner_components = array($inner_components);
        }

        return POP_CLASSPREFIX_MERGE.implode(' '.POP_CLASSPREFIX_MERGE, $inner_components);
    }
}
