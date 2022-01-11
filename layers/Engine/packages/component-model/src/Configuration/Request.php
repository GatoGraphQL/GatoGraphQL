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
use PoP\Definitions\Configuration\Request as DefinitionsRequest;

class Request
{
    public static function getOutput(): string
    {
        $output = $_REQUEST[Params::OUTPUT] ?? null;
        $outputs = [
            Outputs::HTML,
            Outputs::JSON,
        ];
        if (!in_array($output, $outputs)) {
            return Outputs::HTML;
        }
        return $output;
    }

    public static function getDataStructure(): ?string
    {
        return $_REQUEST[Params::DATASTRUCTURE] ?? null;
    }

    public static function getMangledValue(): string
    {
        return DefinitionsRequest::isMangled() ? '' : DefinitionsRequest::URLPARAMVALUE_MANGLED_NONE;
    }

    /**
     * @return string[]
     */
    public static function getActions(): array
    {
        return $_REQUEST[Params::ACTIONS] ?? [];
    }

    public static function getScheme(): ?string
    {
        return $_REQUEST[Params::SCHEME] ?? null;
    }

    public static function getDataSourceSelector(): string
    {
        $dataSourceSelector = $_REQUEST[Params::DATA_SOURCE] ?? null;
        $allDataSourceSelectors = [
            DataSourceSelectors::ONLYMODEL,
            DataSourceSelectors::MODELANDREQUEST,
        ];
        if (!in_array($dataSourceSelector, $allDataSourceSelectors)) {
            return DataSourceSelectors::MODELANDREQUEST;
        }
        return $dataSourceSelector;
    }

    public static function getDataOutputMode(): string
    {
        $dataOutputMode = $_REQUEST[Params::DATAOUTPUTMODE] ?? null;
        $dataOutputModes = [
            DataOutputModes::SPLITBYSOURCES,
            DataOutputModes::COMBINED,
        ];
        if (!in_array($dataOutputMode, $dataOutputModes)) {
            return DataOutputModes::SPLITBYSOURCES;
        }
        return $dataOutputMode;
    }

    public static function getDBOutputMode(): string
    {
        $dbOutputMode = $_REQUEST[Params::DATABASESOUTPUTMODE] ?? null;
        $dbOutputModes = array(
            DatabasesOutputModes::SPLITBYDATABASES,
            DatabasesOutputModes::COMBINED,
        );
        if (!in_array($dbOutputMode, $dbOutputModes)) {
            return DatabasesOutputModes::SPLITBYDATABASES;
        }
        return $dbOutputMode;
    }

    /**
     * @return string[]
     */
    public static function getDataOutputItems(): array
    {
        $dataOutputItems = $_REQUEST[Params::DATA_OUTPUT_ITEMS] ?? [];
        if (!is_array($dataOutputItems)) {
            $dataOutputItems = explode(Param::VALUE_SEPARATOR, $dataOutputItems);
        }

        $dataOutputItems = array_intersect(
            $dataOutputItems,
            static::getAllDataOutputItems()
        );
        if (!$dataOutputItems) {
            return static::getDefaultDataOutputItems();
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

    public static function getActionPath(): ?string
    {
        return $_REQUEST[Params::ACTION_PATH] ?? null;
    }

    /**
     * Indicates the version constraint for all fields/directives in the query
     */
    public static function getVersionConstraint(): ?string
    {
        return $_REQUEST[Params::VERSION_CONSTRAINT] ?? null;
    }

    /**
     * Indicates the version constraints for specific fields in the schema
     */
    public static function getVersionConstraintsForFields(): ?array
    {
        return $_REQUEST[Params::VERSION_CONSTRAINT_FOR_FIELDS] ?? null;
    }

    /**
     * Indicates the version constraints for specific directives in the schema
     */
    public static function getVersionConstraintsForDirectives(): ?array
    {
        return $_REQUEST[Params::VERSION_CONSTRAINT_FOR_DIRECTIVES] ?? null;
    }
}
