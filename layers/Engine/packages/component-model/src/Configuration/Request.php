<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Configuration;

use PoP\ComponentModel\Constants\Outputs;
use PoP\ComponentModel\Constants\Params;
use PoP\Definitions\Configuration\Request as DefinitionsRequest;

class Request
{
    public static function getOutput(): string
    {
        $output = strtolower($_REQUEST[Params::OUTPUT] ?? '');
        $outputs = [
            Outputs::HTML,
            Outputs::JSON,
        ];
        if (!in_array($output, $outputs)) {
            return Outputs::HTML;
        }
        return $output;
    }

    public static function getDataStructure(): string
    {
        return strtolower($_REQUEST[Params::DATASTRUCTURE] ?? '');
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
        return isset($_REQUEST[Params::ACTIONS]) ? array_map('strtolower', $_REQUEST[Params::ACTIONS]) : [];
    }

    public static function getScheme(): string
    {
        return strtolower($_REQUEST[Params::SCHEME] ?? '');
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
