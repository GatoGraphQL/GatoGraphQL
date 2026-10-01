<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Configuration;

use PoP\ComponentModel\Constants\Params;
use PoP\ComponentModel\Enums\DatabasesOutputModes;
use PoP\ComponentModel\Enums\DataOutputItems;
use PoP\ComponentModel\Enums\DataOutputModes;
use PoP\ComponentModel\Enums\DataSourceSelectors;
use PoP\ComponentModel\Enums\Outputs;
use PoP\ComponentModel\Tokens\Param;
use PoP\Root\App;

use function array_filter;
use function array_map;
use function array_values;
use function explode;
use function in_array;
use function is_array;
use function is_string;

/**
 * Special Request class, with properties that modify the Engine's behavior.
 * All methods receive an extra parameter:
 *
 *   $enableModifyingEngineBehaviorViaRequest
 *
 * By setting this flag in false, users cannot modify the behavior of the application,
 * which is defined via AppStateProvider classes.
 */
class EngineRequest
{
    public static function getOutput(bool $enableModifyingEngineBehaviorViaRequest): Outputs
    {
        $default = Outputs::HTML;
        if (!$enableModifyingEngineBehaviorViaRequest) {
            return $default;
        }

        $output = App::request(Params::OUTPUT) ?? App::query(Params::OUTPUT);
        if (!is_string($output)) {
            return $default;
        }
        return Outputs::tryFrom($output) ?? $default;
    }

    public static function getDataStructure(bool $enableModifyingEngineBehaviorViaRequest): ?string
    {
        $default = null;
        if (!$enableModifyingEngineBehaviorViaRequest) {
            return $default;
        }

        return RequestHelpers::getStringOrNullRequestParamValue(
            App::request(Params::DATASTRUCTURE) ?? App::query(Params::DATASTRUCTURE, $default)
        );
    }

    public static function getScheme(bool $enableModifyingEngineBehaviorViaRequest): ?string
    {
        $default = null;
        if (!$enableModifyingEngineBehaviorViaRequest) {
            return $default;
        }

        return RequestHelpers::getStringOrNullRequestParamValue(
            App::request(Params::SCHEME) ?? App::query(Params::SCHEME, $default)
        );
    }

    public static function getDataSourceSelector(bool $enableModifyingEngineBehaviorViaRequest): DataSourceSelectors
    {
        $default = DataSourceSelectors::MODELANDREQUEST;
        if (!$enableModifyingEngineBehaviorViaRequest) {
            return $default;
        }

        $dataSourceSelector = App::request(Params::DATA_SOURCE) ?? App::query(Params::DATA_SOURCE);
        if (!is_string($dataSourceSelector)) {
            return $default;
        }
        return DataSourceSelectors::tryFrom($dataSourceSelector) ?? $default;
    }

    public static function getDataOutputMode(bool $enableModifyingEngineBehaviorViaRequest): DataOutputModes
    {
        $default = DataOutputModes::SPLITBYSOURCES;
        if (!$enableModifyingEngineBehaviorViaRequest) {
            return $default;
        }

        $dataOutputMode = App::request(Params::DATAOUTPUTMODE) ?? App::query(Params::DATAOUTPUTMODE);
        if (!is_string($dataOutputMode)) {
            return $default;
        }
        return DataOutputModes::tryFrom($dataOutputMode) ?? $default;
    }

    public static function getDBOutputMode(bool $enableModifyingEngineBehaviorViaRequest): DatabasesOutputModes
    {
        $default = DatabasesOutputModes::SPLITBYDATABASES;
        if (!$enableModifyingEngineBehaviorViaRequest) {
            return $default;
        }

        $dbOutputMode = App::request(Params::DATABASESOUTPUTMODE) ?? App::query(Params::DATABASESOUTPUTMODE);
        if (!is_string($dbOutputMode)) {
            return $default;
        }
        return DatabasesOutputModes::tryFrom($dbOutputMode) ?? $default;
    }

    /**
     * @return DataOutputItems[]
     */
    public static function getDataOutputItems(bool $enableModifyingEngineBehaviorViaRequest): array
    {
        $default = static::getDefaultDataOutputItems();
        if (!$enableModifyingEngineBehaviorViaRequest) {
            return $default;
        }

        $dataOutputItems = App::getRequest()->request->all()[Params::DATA_OUTPUT_ITEMS] ?? App::getRequest()->query->all()[Params::DATA_OUTPUT_ITEMS] ?? [];
        if (!is_array($dataOutputItems)) {
            $dataOutputItems = explode(Param::VALUE_SEPARATOR, $dataOutputItems);
        }

        $allDataOutputItems = static::getAllDataOutputItems();
        $dataOutputItems = array_values(array_filter(
            array_map(
                fn (mixed $dataOutputItem): ?DataOutputItems => is_string($dataOutputItem) ? DataOutputItems::tryFrom($dataOutputItem) : null,
                $dataOutputItems
            ),
            fn (?DataOutputItems $dataOutputItem): bool => $dataOutputItem !== null && in_array($dataOutputItem, $allDataOutputItems, true)
        ));
        if ($dataOutputItems === []) {
            return $default;
        }
        return $dataOutputItems;
    }

    /**
     * @return DataOutputItems[]
     */
    protected static function getAllDataOutputItems(): array
    {
        return [
            DataOutputItems::META,
            DataOutputItems::DATASET_COMPONENT_SETTINGS,
            DataOutputItems::COMPONENT_DATA,
            DataOutputItems::DATABASES,
            DataOutputItems::SESSION,
        ];
    }

    /**
     * @return DataOutputItems[]
     */
    protected static function getDefaultDataOutputItems(): array
    {
        return [
            DataOutputItems::META,
            DataOutputItems::DATASET_COMPONENT_SETTINGS,
            DataOutputItems::COMPONENT_DATA,
            DataOutputItems::DATABASES,
            DataOutputItems::SESSION,
        ];
    }
}
