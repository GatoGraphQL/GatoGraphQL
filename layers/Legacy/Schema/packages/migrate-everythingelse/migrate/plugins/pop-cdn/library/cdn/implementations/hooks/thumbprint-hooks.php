<?php

class PoP_CDN_ThumbprintUserHooks
{
    public function __construct()
    {
        \PoP\Root\App::getHookManager()->addFilter(
            'PoP_CDN_FileReproduction_ThumbprintsConfig:criteriaitems:thumbprint:startsWith:partial',
            array($this, 'getThumbprintPartialpaths'),
            10,
            2
        );
        \PoP\Root\App::getHookManager()->addFilter(
            'PoP_CDN_FileReproduction_ThumbprintsConfig:criteriaitems:thumbprint:isHome',
            array($this, 'getThumbprintIshome'),
            10,
            2
        );
    }

    public function getThumbprintIshome($ishome, $thumbprint)
    {
        $home_thumbprints = array(
            POP_CDN_THUMBPRINT_USER,
            POP_CDN_THUMBPRINT_POST,
        );
        if (in_array($thumbprint, $home_thumbprints)) {
            // Home needs POST and USER thumbprints
            return true;
        }
        
        return $ishome;
    }

    public function getThumbprintPartialpaths($paths, $thumbprint)
    {
        $author_thumbprints = array(
            POP_CDN_THUMBPRINT_USER,
            POP_CDN_THUMBPRINT_POST,
        );
        if (in_array($thumbprint, $author_thumbprints)) {
            // The author page displays the user information + user posts
            // So simply add the partial path for the author URL slug prefix, eg: 'u/',
            // to catch all URLs for the authors, such as getpop.org/en/u/leo/
            $cmsusersapi = \PoPSchema\Users\FunctionAPIFactory::getInstance();
            $paths[] = $cmsusersapi->getAuthorBase().'/';

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
    
/**
 * Initialize
 */
new PoP_CDN_ThumbprintUserHooks();
