<?php

class PoP_Bootstrap_Utils
{
    public static function getFrontendId($frontend_id, $group)
    {
    
        // As defined in helper generateId in helpers.handlebars.js
        return $frontend_id.POP_CONSTANT_ID_SEPARATOR.$group;
    }
}
