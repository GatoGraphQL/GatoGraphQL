<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Configuration;

use PoP\ComponentModel\Constants\DatabasesOutputModes;
use PoP\ComponentModel\Constants\DataOutputItems;
use PoP\ComponentModel\Constants\DataOutputModes;
use PoP\ComponentModel\Constants\DataSourceSelectors;
use PoP\ComponentModel\Constants\Outputs;
use PoP\ComponentModel\Constants\Params;
use PoP\ComponentModel\Tokens\Param;

/**
 * Special Request class, with properties that modify the Engine's behavior.
 * All methods receive an extra parameter:
 * 
 *   $enableModifyingEngineBehaviorViaRequestParams
 * 
 * By setting this flag in false, users cannot modify the behavior of the application,
 * which is defined via AppStateProvider classes.
 */
class EngineRequest
{
    public static function getOutput(bool $enableModifyingEngineBehaviorViaRequestParams): string
    {
        $default = Outputs::HTML;
        if (!$enableModifyingEngineBehaviorViaRequestParams) {
            return $default;
        }

        $output = $_REQUEST[Params::OUTPUT] ?? null;
        $outputs = [
            Outputs::HTML,
            Outputs::JSON,
        ];
        if (!in_array($output, $outputs)) {
            return $default;
        }
        return $output;
    }

    public static function getDataStructure(bool $enableModifyingEngineBehaviorViaRequestParams): ?string
    {
        $default = null;
        if (!$enableModifyingEngineBehaviorViaRequestParams) {
            return $default;
        }

        return $_REQUEST[Params::DATASTRUCTURE] ?? $default;
    }

    public static function getScheme(bool $enableModifyingEngineBehaviorViaRequestParams): ?string
    {
        $default = null;
        if (!$enableModifyingEngineBehaviorViaRequestParams) {
            return $default;
        }

        return $_REQUEST[Params::SCHEME] ?? $default;
    }

    public static function getDataSourceSelector(bool $enableModifyingEngineBehaviorViaRequestParams): string
    {
        $default = DataSourceSelectors::MODELANDREQUEST;
        if (!$enableModifyingEngineBehaviorViaRequestParams) {
            return $default;
        }

        $dataSourceSelector = $_REQUEST[Params::DATA_SOURCE] ?? null;
        $allDataSourceSelectors = [
            DataSourceSelectors::ONLYMODEL,
            DataSourceSelectors::MODELANDREQUEST,
        ];
        if (!in_array($dataSourceSelector, $allDataSourceSelectors)) {
            return $default;
        }
        return $dataSourceSelector;
    }

    public static function getDataOutputMode(bool $enableModifyingEngineBehaviorViaRequestParams): string
    {
        $default = DataOutputModes::SPLITBYSOURCES;
        if (!$enableModifyingEngineBehaviorViaRequestParams) {
            return $default;
        }

        $dataOutputMode = $_REQUEST[Params::DATAOUTPUTMODE] ?? null;
        $dataOutputModes = [
            DataOutputModes::SPLITBYSOURCES,
            DataOutputModes::COMBINED,
        ];
        if (!in_array($dataOutputMode, $dataOutputModes)) {
            return $default;
        }
        return $dataOutputMode;
    }

    public static function getDBOutputMode(bool $enableModifyingEngineBehaviorViaRequestParams): string
    {
        $default = DatabasesOutputModes::SPLITBYDATABASES;
        if (!$enableModifyingEngineBehaviorViaRequestParams) {
            return $default;
        }

        $dbOutputMode = $_REQUEST[Params::DATABASESOUTPUTMODE] ?? null;
        $dbOutputModes = array(
            DatabasesOutputModes::SPLITBYDATABASES,
            DatabasesOutputModes::COMBINED,
        );
        if (!in_array($dbOutputMode, $dbOutputModes)) {
            return $default;
        }
        return $dbOutputMode;
    }

    /**
     * @return string[]
     */
    public static function getDataOutputItems(bool $enableModifyingEngineBehaviorViaRequestParams): array
    {
        $default = static::getDefaultDataOutputItems();
        if (!$enableModifyingEngineBehaviorViaRequestParams) {
            return $default;
        }

        $dataOutputItems = $_REQUEST[Params::DATA_OUTPUT_ITEMS] ?? [];
        if (!is_array($dataOutputItems)) {
            $dataOutputItems = explode(Param::VALUE_SEPARATOR, $dataOutputItems);
        }

        $dataOutputItems = array_intersect(
            $dataOutputItems,
            static::getAllDataOutputItems()
        );
        if (!$dataOutputItems) {
            return $default;
        }
        return $dataOutputItems;
    }

    /**
     * @return string[]
     */
    protected static function getAllDataOutputItems(): array
    {
        return [
            DataOutputItems::META,
            DataOutputItems::DATASET_MODULE_SETTINGS,
            DataOutputItems::MODULE_DATA,
            DataOutputItems::DATABASES,
            DataOutputItems::SESSION,
        ];
    }

    /**
     * @return string[]
     */
    protected static function getDefaultDataOutputItems(): array
    {
        return [
            DataOutputItems::META,
            DataOutputItems::DATASET_MODULE_SETTINGS,
            DataOutputItems::MODULE_DATA,
            DataOutputItems::DATABASES,
            DataOutputItems::SESSION,
        ];
    }
}
