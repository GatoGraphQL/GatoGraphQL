<?php

namespace PoP\SiteBuilderAPI\Helpers;

use PoP\Root\App;
use PoP\API\Response\Schemes as APISchemes;
use PoP\ComponentModel\Constants\DataOutputItems;
use PoP\ComponentModel\Constants\DataOutputModes;
use PoP\ComponentModel\Constants\Outputs;
use PoP\ComponentModel\Constants\Params;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\Tokens\Param;
use PoP\Definitions\Configuration\Request;

class APIUtils
{
    public static function getEndpoint(string $url, ?array $dataoutputitems = null): string
    {
        $dataoutputitems = $dataoutputitems ?? [
            DataOutputItems::MODULE_DATA,
            DataOutputItems::DATABASES,
            DataOutputItems::DATASET_MODULE_SETTINGS,
        ];
        $endpoint = GeneralUtils::addQueryArgs([
            Params::SCHEME => APISchemes::API,
            Params::OUTPUT => Outputs::JSON,
            Params::DATAOUTPUTMODE => DataOutputModes::COMBINED,
            // \PoP\ComponentModel\Constants\Params::DATABASESOUTPUTMODE => \PoP\ComponentModel\Constants\DatabasesOutputModes::COMBINED,
            Params::DATA_OUTPUT_ITEMS => implode(
                Param::VALUE_SEPARATOR,
                $dataoutputitems
            ),
        ], $url);

        if ($mangled = App::getState('mangled')) {
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
