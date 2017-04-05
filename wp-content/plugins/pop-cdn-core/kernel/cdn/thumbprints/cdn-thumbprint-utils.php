<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class PoP_CDNCore_Thumbprint_Utils {

    public static function convert_url($url, $thumbprints = array()) {

        // Convert the URL to point to the CDNCore domain and use the passed thumbprints
        global $pop_cdncore_thumbprint_manager;

        // Plug-in the CDNCore here, and the needed params
        $homeurl = get_site_url();
        if (POP_CDN_CONTENT_URI && substr($url, 0, strlen($homeurl)) == $homeurl) {

            // Replace the home url with the CDNCore domain
            $url = POP_CDN_CONTENT_URI.substr($url, strlen($homeurl));

            // Add the version
            // $url = add_query_arg(POP_CDNCORE_URLPARAM_VERSION, pop_version(), $url);

            // Add the thumbprints
            $thumbprints_value = array();
            foreach ($thumbprints as $thumbprint) {
                
                $thumbprints_value[] = $pop_cdncore_thumbprint_manager->get_thumbprint_value($thumbprint);
            }
            $url = add_query_arg(GD_URLPARAM_CDNTHUMBPRINT, implode(POP_CDNCORE_SEPARATOR_THUMBPRINT, $thumbprints_value), $url);
        }

        return $url;
    }
}

