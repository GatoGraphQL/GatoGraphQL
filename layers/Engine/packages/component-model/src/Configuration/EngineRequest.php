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
 *   $enableModifyingEngineBehaviorViaRequest
 *
 * By setting this flag in false, users cannot modify the behavior of the application,
 * which is defined via AppStateProvider classes.
 */
class EngineRequest
{
    public static function getOutput(bool $enableModifyingEngineBehaviorViaRequest): string
    {
        $default = Outputs::HTML;
        if (!$enableModifyingEngineBehaviorViaRequest) {
            return $default;
        }

        $output = $_POST[Params::OUTPUT] ?? \PoP\Root\App::query(Params::OUTPUT);
        $outputs = [
            Outputs::HTML,
            Outputs::JSON,
        ];
        if (!in_array($output, $outputs)) {
            return $default;
        }
        return $output;
    }

    public static function getDataStructure(bool $enableModifyingEngineBehaviorViaRequest): ?string
    {
        $default = null;
        if (!$enableModifyingEngineBehaviorViaRequest) {
            return $default;
        }

        return $_POST[Params::DATASTRUCTURE] ?? $_GET[Params::DATASTRUCTURE] ?? $default;
    }

    public static function getScheme(bool $enableModifyingEngineBehaviorViaRequest): ?string
    {
        $default = null;
        if (!$enableModifyingEngineBehaviorViaRequest) {
            return $default;
        }

        return $_POST[Params::SCHEME] ?? $_GET[Params::SCHEME] ?? $default;
    }

    public static function getDataSourceSelector(bool $enableModifyingEngineBehaviorViaRequest): string
    {
        $default = DataSourceSelectors::MODELANDREQUEST;
        if (!$enableModifyingEngineBehaviorViaRequest) {
            return $default;
        }

        $dataSourceSelector = $_POST[Params::DATA_SOURCE] ?? \PoP\Root\App::query(Params::DATA_SOURCE);
        $allDataSourceSelectors = [
            DataSourceSelectors::ONLYMODEL,
            DataSourceSelectors::MODELANDREQUEST,
        ];
        if (!in_array($dataSourceSelector, $allDataSourceSelectors)) {
            return $default;
        }
        return $dataSourceSelector;
    }

    public static function getDataOutputMode(bool $enableModifyingEngineBehaviorViaRequest): string
    {
        $default = DataOutputModes::SPLITBYSOURCES;
        if (!$enableModifyingEngineBehaviorViaRequest) {
            return $default;
        }

        $dataOutputMode = $_POST[Params::DATAOUTPUTMODE] ?? \PoP\Root\App::query(Params::DATAOUTPUTMODE);
        $dataOutputModes = [
            DataOutputModes::SPLITBYSOURCES,
            DataOutputModes::COMBINED,
        ];
        if (!in_array($dataOutputMode, $dataOutputModes)) {
            return $default;
        }
        return $dataOutputMode;
    }

    public static function getDBOutputMode(bool $enableModifyingEngineBehaviorViaRequest): string
    {
        $default = DatabasesOutputModes::SPLITBYDATABASES;
        if (!$enableModifyingEngineBehaviorViaRequest) {
            return $default;
        }

        $dbOutputMode = $_POST[Params::DATABASESOUTPUTMODE] ?? \PoP\Root\App::query(Params::DATABASESOUTPUTMODE);
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
    public static function getDataOutputItems(bool $enableModifyingEngineBehaviorViaRequest): array
    {
        $default = static::getDefaultDataOutputItems();
        if (!$enableModifyingEngineBehaviorViaRequest) {
            return $default;
        }

        $dataOutputItems = $_POST[Params::DATA_OUTPUT_ITEMS] ?? $_GET[Params::DATA_OUTPUT_ITEMS] ?? [];
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
