<?php
use PoP\ComponentModel\Misc\GeneralUtils;

class GD_Settings_UrlOperator_Format extends GD_Settings_UrlOperator
{
    public function getUrl($url, $field, $value)
    {
        switch ($field) {
            case self::MODULE_FORMINPUT_SETTINGSFORMAT:
                $url = GeneralUtils::addQueryArgs([
                	\PoP\ConfigurationComponentModel\Constants\Params::FORMAT => $value,
                ], $url);
                break;
        }

        return $url;
    }
}
