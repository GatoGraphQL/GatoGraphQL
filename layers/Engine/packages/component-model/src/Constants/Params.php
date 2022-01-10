<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Constants;

class Params
{
    public const VERSION = 'version';
    public const DATASTRUCTURE = 'datastructure';
    public const FORMAT = 'format';
    public const SETTINGSFORMAT = 'settingsformat';
    public const DATAOUTPUTMODE = 'dataoutputmode';
    public const DATABASESOUTPUTMODE = 'dboutputmode';
    public const ACTIONS = 'actions';
    public const SCHEME = 'scheme';
    // Paged param: It is 'pagenum' and not 'paged', because if so WP does a redirect to re-adjust the URL
    // From https://www.mesym.com/action?paged=2 it redirects to https://www.mesym.com/action/paged/2/
    public const PAGE_NUMBER = 'pagenum';
    public const LIMIT = 'limit';
    public const ACTION_PATH = 'actionpath';
    public const DATA_OUTPUT_ITEMS = 'dataoutputitems';
    public const DATA_SOURCE = 'datasource';
    public const EXTRA_ROUTES = 'extraroutes';
    public const OUTPUT = 'output';
    public const TARGET = 'target';
}
