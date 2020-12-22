<?php

class PoPTheme_Wassup_FrontEnd_MultiDomain_SPAResourceLoader_Utils
{
    public static function addConfigSources(&$sources, $domain, $website_name, $options)
    {
        
        // There are 2 files per external domain: the config.js file, and the resources.js file
        global $pop_sparesourceloader_configfile, $pop_sparesourceloader_resources_configfile;
        $config_url = $pop_sparesourceloader_configfile->getFileurl();
        $resources_url = $pop_sparesourceloader_resources_configfile->getFileurl();

        $sources[$domain] = array(
            PoP_MultiDomain_Utils::transformUrl($config_url, $domain, $website_name, $options),
            PoP_MultiDomain_Utils::transformUrl($resources_url, $domain, $website_name, $options),
        );
    }
}
