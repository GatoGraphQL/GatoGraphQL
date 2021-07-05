<?php

namespace PoP\SiteBuilderAPI\Helpers;

use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Definitions\Configuration\Request;
use PoP\API\Response\Schemes as APISchemes;

class APIUtils
{
    public static function getEndpoint(string $url, ?array $dataoutputitems = null): string
    {
        $dataoutputitems = $dataoutputitems ?? [
            \PoP\ComponentModel\Constants\DataOutputItems::MODULE_DATA,
            \PoP\ComponentModel\Constants\DataOutputItems::DATABASES,
            \PoP\ComponentModel\Constants\DataOutputItems::DATASET_MODULE_SETTINGS,
        ];
        $endpoint = GeneralUtils::addQueryArgs([
            \PoP\ComponentModel\Constants\Params::SCHEME => APISchemes::API,
            \PoP\ComponentModel\Constants\Params::OUTPUT => \PoP\ComponentModel\Constants\Outputs::JSON,
            \PoP\ComponentModel\Constants\Params::DATAOUTPUTMODE => \PoP\ComponentModel\Constants\DataOutputModes::COMBINED,
            // \PoP\ComponentModel\Constants\Params::DATABASESOUTPUTMODE => \PoP\ComponentModel\Constants\DatabasesOutputModes::COMBINED,
            \PoP\ComponentModel\Constants\Params::DATA_OUTPUT_ITEMS => implode(
                \PoP\ComponentModel\Tokens\Param::VALUE_SEPARATOR,
                $dataoutputitems
            ),
        ], $url);

        $vars = ApplicationState::getVars();
        if ($mangled = $vars['mangled']) {
            $endpoint = GeneralUtils::addQueryArgs(
                [
                    Request::URLPARAM_MANGLED => $mangled,
                ],
                $endpoint
            );
        }

        return $endpoint;
    }
}
