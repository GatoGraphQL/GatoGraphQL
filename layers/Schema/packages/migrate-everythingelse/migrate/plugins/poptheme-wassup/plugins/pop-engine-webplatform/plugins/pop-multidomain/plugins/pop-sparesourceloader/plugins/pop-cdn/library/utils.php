<?php

class PoPTheme_Wassup_FrontEnd_MultiDomain_SPAResourceLoader_CDN_Utils
{
    public static function addConfigSources(&$sources, $domain, $website_name)
    {
        global $pop_cdn_configfile;
        $cdnconfig_url = $pop_cdn_configfile->getFileurl();

        $sources[$domain] = array(
            PoP_MultiDomain_Utils::transformUrl($cdnconfig_url, $domain, $website_name),
        );
    }
}
