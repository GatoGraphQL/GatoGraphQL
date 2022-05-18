<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Constants;

class Params
{
    public final const VERSION = 'version';
    public final const DATASTRUCTURE = 'datastructure';
    public final const DATAOUTPUTMODE = 'dataoutputmode';
    public final const DATABASESOUTPUTMODE = 'dboutputmode';
    public final const ACTIONS = 'actions';
    public final const SCHEME = 'scheme';
    public final const ACTION_PATH = 'actionpath';
    public final const DATA_OUTPUT_ITEMS = 'dataoutputitems';
    public final const DATA_SOURCE = 'datasource';
    public final const EXTRA_ROUTES = 'extraroutes';
    public final const OUTPUT = 'output';
    public final const MODULEFILTER = 'modulefilter';
    public final const MODULEPATHS = 'componentVariationPaths';

    /**
     * What version constraint to use for the API
     */
    public final const VERSION_CONSTRAINT = 'versionConstraints';
    public final const VERSION_CONSTRAINT_FOR_FIELDS = 'fieldVersionConstraints';
    public final const VERSION_CONSTRAINT_FOR_DIRECTIVES = 'directiveVersionConstraints';
}
