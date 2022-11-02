<?php

declare(strict_types=1);

namespace PoP\SiteBuilderAPI\Helpers;

use PoP\Root\App;
use PoPAPI\API\Response\Schemes as APISchemes;
use PoP\ComponentModel\Constants\DataOutputItems;
use PoP\ComponentModel\Constants\DataOutputModes;
use PoP\ComponentModel\Constants\Outputs;
use PoP\ComponentModel\Constants\Params;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\Tokens\Param;
use PoP\Definitions\Constants\Params as DefinitionsParams;

class APIUtils
{
    public static function getEndpoint(string $url, ?array $dataoutputitems = null): string
    {
        $dataoutputitems = $dataoutputitems ?? [
            DataOutputItems::COMPONENT_DATA,
            DataOutputItems::DATABASES,
            DataOutputItems::DATASET_COMPONENT_SETTINGS,
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
                    DefinitionsParams::MANGLED => $mangled,
                ],
                $endpoint
            );
        }

        return $endpoint;
    }
}
