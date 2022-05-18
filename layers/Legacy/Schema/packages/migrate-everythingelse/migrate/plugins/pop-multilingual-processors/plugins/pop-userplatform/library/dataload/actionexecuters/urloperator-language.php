<?php

class GD_QT_Settings_UrlOperator_Language extends GD_Settings_UrlOperator
{
    public function getUrl($url, $field, $value)
    {
        switch ($field) {
            case self::COMPONENT_QT_FORMINPUT_LANGUAGE:
                $language = $value;

                $pluginapi = PoP_Multilingual_FunctionsAPIFactory::getInstance();
                $url = $pluginapi->convertUrl($url, $language);

                // Delete the qtrans_front_language cookie so that the plug-in doesn't get confused and redirects to MS again instead of EN (since EN is default, lang not available in URL)
                $pluginapi->setLanguageCookie($language);
                break;
        }
    
        return $url;
    }
}
