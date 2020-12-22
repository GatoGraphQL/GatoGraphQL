<?php

interface PoP_Multilingual_FunctionsAPI
{
    public function getCurrentLanguage();
    public function getLanguageName($language);
    public function convertUrl($url, $language);
    public function getEnabledLanguages();
    public function getUrlModificationMode();
    public function setLanguageCookie($language);
}
