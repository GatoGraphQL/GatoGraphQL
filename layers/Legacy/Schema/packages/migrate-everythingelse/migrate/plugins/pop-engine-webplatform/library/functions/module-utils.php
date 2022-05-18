<?php

class PoP_WebPlatformEngine_Module_Utils
{
    public static function getMergeClass($inner_componentVariations)
    {
        if (!is_array($inner_componentVariations)) {
            $inner_componentVariations = array($inner_componentVariations);
        }

        return POP_CLASSPREFIX_MERGE.implode(' '.POP_CLASSPREFIX_MERGE, $inner_componentVariations);
    }
}
