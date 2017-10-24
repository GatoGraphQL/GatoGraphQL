<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class PoP_CDNCore_ThumbprintUserHooks {

    function __construct() {

        add_filter(
            'PoP_CDNCore_FileReproduction_ThumbprintsConfig:criteriaitems:thumbprint:startsWith:partial',
            array($this, 'get_thumbprint_partialpaths'),
            10,
            2
        );
        add_filter(
            'PoP_CDNCore_FileReproduction_ThumbprintsConfig:criteriaitems:thumbprint:isHome',
            array($this, 'get_thumbprint_ishome'),
            10,
            2
        );
    }

    function get_thumbprint_ishome($ishome, $thumbprint) {
        
        $home_thumbprints = array(
            POP_CDNCORE_THUMBPRINT_USER,
            POP_CDNCORE_THUMBPRINT_POST,
        );
        if (in_array($thumbprint, $home_thumbprints)) {

            // Home needs POST and USER thumbprints
            return true;
        }
        
        return $ishome;
    }

    function get_thumbprint_partialpaths($paths, $thumbprint) {
        
        $author_thumbprints = array(
            POP_CDNCORE_THUMBPRINT_USER,
            POP_CDNCORE_THUMBPRINT_POST,
        );
        if (in_array($thumbprint, $author_thumbprints)) {

            // The author page displays the user information + user posts
            // So simply add the partial path for the author URL slug prefix, eg: 'u/',
            // to catch all URLs for the authors, such as getpop.org/en/u/leo/
            global $wp_rewrite;
            $paths[] = $wp_rewrite->author_base.'/';

            // Please notice: 
            // getpop.org/en/u/leo/ has thumbprints POST + USER, but
            // getpop.org/en/u/leo/?tab=description needs only thumbprint USER
            // for that, we have added criteria "noParamValues", checking that it is thumbprint POST
            // as long as the specified tabs (description, followers, etc) are not in the URL
            // This must be added on those hooks.php files implementing the corresponding pages
            // (eg: pop-coreprocessors handles tab=description, etc)
        }
        
        return $paths;
    }
}
    
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_CDNCore_ThumbprintUserHooks();
