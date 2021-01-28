<?php

declare(strict_types=1);

namespace PoP\ComponentModel\State;

use PoP\ComponentModel\Constants\Params;
use PoP\ComponentModel\Constants\Outputs;
use PoP\ComponentModel\Constants\DataSourceSelectors;
use PoP\ComponentModel\Constants\DataOutputModes;
use PoP\ComponentModel\Constants\DatabasesOutputModes;
use PoP\ComponentModel\Tokens\Param;
use PoP\ComponentModel\Constants\DataOutputItems;
use PoP\ComponentModel\Constants\Targets;
use PoP\ComponentModel\Constants\Values;
use PoP\Routing\RouteNatures;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\Configuration\Request;
use PoP\Routing\Facades\RoutingManagerFacade;
use PoP\ComponentModel\Facades\Info\ApplicationInfoFacade;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\ComponentModel\Facades\ModuleFiltering\ModuleFilterManagerFacade;
use PoP\ComponentModel\ComponentConfiguration;
use PoP\ComponentModel\StratumManagerFactory;
use PoP\ComponentModel\Environment as Environment;

class ApplicationState
{
    /**
     * @var array<string, mixed>
     */
    public static array $vars = [];

    /**
     * @return array<string, mixed>
     */
    public static function getVars(): array
    {
        if (self::$vars) {
            return self::$vars;
        }

        // Only initialize the first time. Then, it will call ->resetState() to retrieve new state, no need to create a new instance
        $routingManager = RoutingManagerFacade::getInstance();
        $nature = $routingManager->getCurrentNature();
        $route = $routingManager->getCurrentRoute();

        // Convert them to lower to make it insensitive to upper/lower case values
        $output = strtolower($_REQUEST[Params::OUTPUT] ?? '');
        $dataoutputitems = $_REQUEST[Params::DATA_OUTPUT_ITEMS] ?? [];
        $datasources = strtolower($_REQUEST[Params::DATA_SOURCE] ?? '');
        $datastructure = strtolower($_REQUEST[Params::DATASTRUCTURE] ?? '');
        $dataoutputmode = strtolower($_REQUEST[Params::DATAOUTPUTMODE] ?? '');
        $dboutputmode = strtolower($_REQUEST[Params::DATABASESOUTPUTMODE] ?? '');
        $target = strtolower($_REQUEST[Params::TARGET] ?? '');
        $mangled = Request::isMangled() ? '' : Request::URLPARAMVALUE_MANGLED_NONE;
        $actions = isset($_REQUEST[Params::ACTIONS]) ?
            array_map('strtolower', $_REQUEST[Params::ACTIONS]) : [];
        $scheme = strtolower($_REQUEST[Params::SCHEME] ?? '');
        // The version could possibly be set from outside
        $version = Environment::enableVersionByParams() ?
            $_REQUEST[Params::VERSION] ?? ApplicationInfoFacade::getInstance()->getVersion()
            : ApplicationInfoFacade::getInstance()->getVersion();

        $outputs = (array) HooksAPIFacade::getInstance()->applyFilters(
            'ApplicationState:outputs',
            array(
                Outputs::HTML,
                Outputs::JSON,
            )
        );
        if (!in_array($output, $outputs)) {
            $output = Outputs::HTML;
        }

        // Target/Module default values (for either empty, or if the user is playing around with the url)
        $alldatasources = array(
            DataSourceSelectors::ONLYMODEL,
            DataSourceSelectors::MODELANDREQUEST,
        );
        if (!in_array($datasources, $alldatasources)) {
            $datasources = DataSourceSelectors::MODELANDREQUEST;
        }

        $dataoutputmodes = array(
            DataOutputModes::SPLITBYSOURCES,
            DataOutputModes::COMBINED,
        );
        if (!in_array($dataoutputmode, $dataoutputmodes)) {
            $dataoutputmode = DataOutputModes::SPLITBYSOURCES;
        }

        $dboutputmodes = array(
            DatabasesOutputModes::SPLITBYDATABASES,
            DatabasesOutputModes::COMBINED,
        );
        if (!in_array($dboutputmode, $dboutputmodes)) {
            $dboutputmode = DatabasesOutputModes::SPLITBYDATABASES;
        }

        if ($dataoutputitems) {
            if (!is_array($dataoutputitems)) {
                $dataoutputitems = explode(Param::VALUE_SEPARATOR, strtolower($dataoutputitems));
            } else {
                $dataoutputitems = array_map('strtolower', $dataoutputitems);
            }
        }
        $alldataoutputitems = (array) HooksAPIFacade::getInstance()->applyFilters(
            'ApplicationState:dataoutputitems',
            array(
                DataOutputItems::META,
                DataOutputItems::DATASET_MODULE_SETTINGS,
                DataOutputItems::MODULE_DATA,
                DataOutputItems::DATABASES,
                DataOutputItems::SESSION,
            )
        );
        $dataoutputitems = array_intersect(
            $dataoutputitems ?? array(),
            $alldataoutputitems
        );
        if (!$dataoutputitems) {
            $dataoutputitems = HooksAPIFacade::getInstance()->applyFilters(
                'ApplicationState:default-dataoutputitems',
                array(
                    DataOutputItems::META,
                    DataOutputItems::DATASET_MODULE_SETTINGS,
                    DataOutputItems::MODULE_DATA,
                    DataOutputItems::DATABASES,
                    DataOutputItems::SESSION,
                )
            );
        }

        // If not target, or invalid, reset it to "main"
        // We allow an empty target if none provided, so that we can generate the settings cache when no target is provided
        // (ie initial load) and when target is provided (ie loading pageSection)
        $targets = (array) HooksAPIFacade::getInstance()->applyFilters(
            'ApplicationState:targets',
            array(
                Targets::MAIN,
            )
        );
        if (!in_array($target, $targets)) {
            $target = Targets::MAIN;
        }

        $platformmanager = StratumManagerFactory::getInstance();
        $stratum = $platformmanager->getStratum();
        $strata = $platformmanager->getStrata($stratum);
        $stratum_isdefault = $platformmanager->isDefaultStratum();

        $modulefilter_manager = ModuleFilterManagerFacade::getInstance();
        $modulefilter = $modulefilter_manager->getSelectedModuleFilterName();

        // If there is not format, then set it to 'default'
        // This is needed so that the /generate/ generated configurations under a $model_instance_id (based on the value of $vars)
        // can match the same $model_instance_id when visiting that page
        $format = isset($_REQUEST[Params::FORMAT]) ? strtolower($_REQUEST[Params::FORMAT]) : Values::DEFAULT;

        // By default, get the variables from the request
        $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
        $variables = $fieldQueryInterpreter->getVariablesFromRequest();

        self::$vars = array(
            'nature' => $nature,
            'route' => $route,
            'output' => $output,
            'modulefilter' => $modulefilter,
            'actionpath' => $_REQUEST[Params::ACTION_PATH] ?? '',
            'target' => $target,
            'dataoutputitems' => $dataoutputitems,
            'datasources' => $datasources,
            'datastructure' => $datastructure,
            'dataoutputmode' => $dataoutputmode,
            'dboutputmode' => $dboutputmode,
            'mangled' => $mangled,
            'format' => $format,
            'actions' => $actions,
            'scheme' => $scheme,
            'stratum' => $stratum,
            'strata' => $strata,
            'stratum-isdefault' => $stratum_isdefault,
            'version' => $version,
            'variables' => $variables,
            'only-fieldname-as-outputkey' => false,
            'namespace-types-and-interfaces' => ComponentConfiguration::namespaceTypesAndInterfaces(),
            'version-constraint' => Request::getVersionConstraint(),
            'field-version-constraints' => Request::getVersionConstraintsForFields(),
            'directive-version-constraints' => Request::getVersionConstraintsForDirectives(),
            // By default, mutations are always enabled. Can be changed for the API
            'are-mutations-enabled' => true,
        );

        if (ComponentConfiguration::enableConfigByParams()) {
            self::$vars['config'] = $_REQUEST[Params::CONFIG] ?? null;
        }

        // Set the routing state (eg: PoP Queried Object can add its information)
        self::$vars['routing-state'] = [];

        // Allow for plug-ins to add their own vars
        HooksAPIFacade::getInstance()->doAction(
            'ApplicationState:addVars',
            array(&self::$vars)
        );

        self::augmentVarsProperties();

        return self::$vars;
    }

    public static function augmentVarsProperties()
    {
        $nature = self::$vars['nature'];
        self::$vars['routing-state']['is-standard'] = $nature == RouteNatures::STANDARD;
        self::$vars['routing-state']['is-home'] = $nature == RouteNatures::HOME;
        self::$vars['routing-state']['is-404'] = $nature == RouteNatures::NOTFOUND;

        HooksAPIFacade::getInstance()->doAction(
            'augmentVarsProperties',
            array(&self::$vars)
        );
    }
}
