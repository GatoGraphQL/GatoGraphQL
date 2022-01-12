<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Constants;

class Params
{
    public const VERSION = 'version';
    public const DATASTRUCTURE = 'datastructure';
    public const DATAOUTPUTMODE = 'dataoutputmode';
    public const DATABASESOUTPUTMODE = 'dboutputmode';
    public const ACTIONS = 'actions';
    public const SCHEME = 'scheme';
    public const ACTION_PATH = 'actionpath';
    public const DATA_OUTPUT_ITEMS = 'dataoutputitems';
    public const DATA_SOURCE = 'datasource';
    public const EXTRA_ROUTES = 'extraroutes';
    public const OUTPUT = 'output';
    public const MODULEFILTER = 'modulefilter';
    public const MODULEPATHS = 'modulepaths';

    /**
     * What version constraint to use for the API
     */
    public const VERSION_CONSTRAINT = 'versionConstraints';
    public const VERSION_CONSTRAINT_FOR_FIELDS = 'fieldVersionConstraints';
    public const VERSION_CONSTRAINT_FOR_DIRECTIVES = 'directiveVersionConstraints';
    
}
