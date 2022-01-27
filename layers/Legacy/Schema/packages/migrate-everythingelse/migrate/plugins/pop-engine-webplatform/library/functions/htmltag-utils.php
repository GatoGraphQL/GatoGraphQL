<?php

class PoP_HTMLTags_Utils
{
    protected static $scripttag_attributes;
    
    public function init()
    {
    
        // Allow to add attributes 'async' or 'defer' to the script tag
        // Taken from https://stackoverflow.com/questions/18944027/how-do-i-defer-or-async-this-wordpress-javascript-snippet-to-load-lastly-for-fas
        \PoP\Root\App::addFilter(
            'popcms:scriptTag',
            array(PoP_HTMLTags_Utils::class, 'maybeAddScripttagAttributes'),
            PHP_INT_MAX,
            3
        );
    }

    // Allow to add attributes 'async' or 'defer' to the script tag
    public function maybeAddScripttagAttributes($tag, $handle, $src)
    {

        // Initialize
        if (is_null(self::$scripttag_attributes)) {
            self::$scripttag_attributes = \PoP\Root\App::applyFilters(
                'PoP_HTMLTags_Utils:scripttag_attributes',
                array()
            );
        }
        
        if ($attributes = self::$scripttag_attributes[$handle]) {
            return str_replace(
                " src='${src}'>",
                " src='${src}' ".$attributes.">",
                $tag
            );
        }

        return $tag;
    }
}

/**
 * Initialization
 */
PoP_HTMLTags_Utils::init();
