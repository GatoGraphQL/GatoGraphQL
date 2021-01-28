<?php
namespace PoP\API;

use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\Configuration\Request;
use PoP\API\Response\Schemes as APISchemes;

class APIUtils
{
    public static function getEndpoint($url, $dataoutputitems = null): string
    {
        $dataoutputitems = $dataoutputitems ?? [
            GD_URLPARAM_DATAOUTPUTITEMS_MODULEDATA,
            GD_URLPARAM_DATAOUTPUTITEMS_DATABASES,
            GD_URLPARAM_DATAOUTPUTITEMS_DATASETMODULESETTINGS,
        ];
        $endpoint = GeneralUtils::addQueryArgs([
            GD_URLPARAM_SCHEME => APISchemes::API,
            GD_URLPARAM_OUTPUT => GD_URLPARAM_OUTPUT_JSON,
            \PoP\ComponentModel\Constants\Params::DATAOUTPUTMODE => \PoP\ComponentModel\Constants\DataOutputModes::COMBINED,
            // \PoP\ComponentModel\Constants\Params::DATABASESOUTPUTMODE => \PoP\ComponentModel\Constants\DatabasesOutputModes::COMBINED,
            GD_URLPARAM_DATAOUTPUTITEMS => implode(
                \PoP\ComponentModel\Tokens\Param::VALUE_SEPARATOR,
                $dataoutputitems
            ),
        ], $url);

        $vars = ApplicationState::getVars();
        if ($mangled = $vars['mangled']) {
            $endpoint = GeneralUtils::addQueryArgs([
                Request::URLPARAM_MANGLED => $mangled,
            ], $endpoint);
        }

        return $endpoint;
    }
}
