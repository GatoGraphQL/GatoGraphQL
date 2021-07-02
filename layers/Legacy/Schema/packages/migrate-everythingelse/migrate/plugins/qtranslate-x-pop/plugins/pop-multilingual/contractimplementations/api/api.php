<?php

class QTX_PoP_Multilingual_FunctionsAPI extends PoP_Multilingual_FunctionsAPI_Base implements PoP_Multilingual_FunctionsAPI
{
    public function getCurrentLanguage()
    {
        return qtranxf_getLanguage();
    }

    public function getLanguageName($language)
    {
        return qtranxf_getLanguageName($language);
    }

    public function convertUrl($url, $language)
    {
        return qtranxf_convertURL($url, $language);
    }

    public function getEnabledLanguages()
    {
        global $q_config;
        return $q_config['enabled_languages'];
    }

    public function setLanguageCookie($language)
    {
        qtranxf_set_language_cookie($language);
    }
}

/**
 * Initialize
 */
new QTX_PoP_Multilingual_FunctionsAPI();
