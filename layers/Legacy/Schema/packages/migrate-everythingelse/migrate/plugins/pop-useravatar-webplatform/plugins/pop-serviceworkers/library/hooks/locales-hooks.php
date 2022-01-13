<?php

class PoP_UserAvatarProcessors_ServiceWorkers_Hooks_Locales
{
    public function __construct()
    {
        \PoP\Root\App::getHookManager()->addFilter(
            'PoP_ServiceWorkers_Job_CacheResources:precache',
            array($this, 'getPrecacheList'),
            10,
            2
        );
    }

    public function getPrecacheList($precache, $resourceType)
    {
        if ($resourceType == 'static') {
            // Add all the locales other than the one already added through wp_enqueue_script
            $current = popUseravatarGetLocaleJsfile();
            foreach (glob(POP_USERAVATARPROCESSORS_DIR."/js/locales/fileupload/*") as $file) {
                $fileurl = str_replace(POP_USERAVATARPROCESSORS_DIR, POP_USERAVATARPROCESSORS_URL, $file);
                if ($fileurl != $current) {
                    $precache[] = $fileurl;
                }
            }
        }
        
        return $precache;
    }
}
    
/**
 * Initialize
 */
new PoP_UserAvatarProcessors_ServiceWorkers_Hooks_Locales();
