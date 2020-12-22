<?php

declare(strict_types=1);

namespace PoP\ComponentModel\State;

use PoP\Routing\RouteNatures;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\Configuration\Request;
use PoP\Routing\Facades\RoutingManagerFacade;
use PoP\ComponentModel\Facades\Info\ApplicationInfoFacade;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\ComponentModel\Facades\ModuleFiltering\ModuleFilterManagerFacade;
use PoP\ComponentModel\ComponentConfiguration;
use PoP\ComponentModel\StratumManagerFactory;
use PoP\ComponentModel\Server\Utils as ServerUtils;

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
        $output = strtolower($_REQUEST[\GD_URLPARAM_OUTPUT] ?? '');
        $dataoutputitems = $_REQUEST[\GD_URLPARAM_DATAOUTPUTITEMS] ?? [];
        $datasources = strtolower($_REQUEST[\GD_URLPARAM_DATASOURCES] ?? '');
        $datastructure = strtolower($_REQUEST[\GD_URLPARAM_DATASTRUCTURE] ?? '');
        $dataoutputmode = strtolower($_REQUEST[\GD_URLPARAM_DATAOUTPUTMODE] ?? '');
        $dboutputmode = strtolower($_REQUEST[\GD_URLPARAM_DATABASESOUTPUTMODE] ?? '');
        $target = strtolower($_REQUEST[\GD_URLPARAM_TARGET] ?? '');
        $mangled = Request::isMangled() ? '' : Request::URLPARAMVALUE_MANGLED_NONE;
        $actions = isset($_REQUEST[\GD_URLPARAM_ACTIONS]) ?
            array_map('strtolower', $_REQUEST[\GD_URLPARAM_ACTIONS]) : [];
        $scheme = strtolower($_REQUEST[\GD_URLPARAM_SCHEME] ?? '');
        // The version could possibly be set from outside
        $version = ServerUtils::enableVersionByParams() ?
            $_REQUEST[\GD_URLPARAM_VERSION] ?? ApplicationInfoFacade::getInstance()->getVersion()
            : ApplicationInfoFacade::getInstance()->getVersion();

        $outputs = (array) HooksAPIFacade::getInstance()->applyFilters(
            'ApplicationState:outputs',
            array(
                \GD_URLPARAM_OUTPUT_HTML,
                \GD_URLPARAM_OUTPUT_JSON,
            )
        );
        if (!in_array($output, $outputs)) {
            $output = \GD_URLPARAM_OUTPUT_HTML;
        }

        // Target/Module default values (for either empty, or if the user is playing around with the url)
        $alldatasources = array(
            \GD_URLPARAM_DATASOURCES_ONLYMODEL,
            \GD_URLPARAM_DATASOURCES_MODELANDREQUEST,
        );
        if (!in_array($datasources, $alldatasources)) {
            $datasources = \GD_URLPARAM_DATASOURCES_MODELANDREQUEST;
        }

        $dataoutputmodes = array(
            \GD_URLPARAM_DATAOUTPUTMODE_SPLITBYSOURCES,
            \GD_URLPARAM_DATAOUTPUTMODE_COMBINED,
        );
        if (!in_array($dataoutputmode, $dataoutputmodes)) {
            $dataoutputmode = \GD_URLPARAM_DATAOUTPUTMODE_SPLITBYSOURCES;
        }

        $dboutputmodes = array(
            \GD_URLPARAM_DATABASESOUTPUTMODE_SPLITBYDATABASES,
            \GD_URLPARAM_DATABASESOUTPUTMODE_COMBINED,
        );
        if (!in_array($dboutputmode, $dboutputmodes)) {
            $dboutputmode = \GD_URLPARAM_DATABASESOUTPUTMODE_SPLITBYDATABASES;
        }

        if ($dataoutputitems) {
            if (!is_array($dataoutputitems)) {
                $dataoutputitems = explode(\POP_CONSTANT_PARAMVALUE_SEPARATOR, strtolower($dataoutputitems));
            } else {
                $dataoutputitems = array_map('strtolower', $dataoutputitems);
            }
        }
        $alldataoutputitems = (array) HooksAPIFacade::getInstance()->applyFilters(
            'ApplicationState:dataoutputitems',
            array(
                \GD_URLPARAM_DATAOUTPUTITEMS_META,
                \GD_URLPARAM_DATAOUTPUTITEMS_DATASETMODULESETTINGS,
                \GD_URLPARAM_DATAOUTPUTITEMS_MODULEDATA,
                \GD_URLPARAM_DATAOUTPUTITEMS_DATABASES,
                \GD_URLPARAM_DATAOUTPUTITEMS_SESSION,
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
                    \GD_URLPARAM_DATAOUTPUTITEMS_META,
                    \GD_URLPARAM_DATAOUTPUTITEMS_DATASETMODULESETTINGS,
                    \GD_URLPARAM_DATAOUTPUTITEMS_MODULEDATA,
                    \GD_URLPARAM_DATAOUTPUTITEMS_DATABASES,
                    \GD_URLPARAM_DATAOUTPUTITEMS_SESSION,
                )
            );
        }

        // If not target, or invalid, reset it to "main"
        // We allow an empty target if none provided, so that we can generate the settings cache when no target is provided
        // (ie initial load) and when target is provided (ie loading pageSection)
        $targets = (array) HooksAPIFacade::getInstance()->applyFilters(
            'ApplicationState:targets',
            array(
                \POP_TARGET_MAIN,
            )
        );
        if (!in_array($target, $targets)) {
            $target = \POP_TARGET_MAIN;
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
        $format = isset($_REQUEST[\GD_URLPARAM_FORMAT]) ? strtolower($_REQUEST[\GD_URLPARAM_FORMAT]) : \POP_VALUES_DEFAULT;

        // By default, get the variables from the request
        $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
        $variables = $fieldQueryInterpreter->getVariablesFromRequest();

        self::$vars = array(
            'nature' => $nature,
            'route' => $route,
            'output' => $output,
            'modulefilter' => $modulefilter,
            'actionpath' => $_REQUEST[\GD_URLPARAM_ACTIONPATH] ?? '',
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
            self::$vars['config'] = $_REQUEST[\POP_URLPARAM_CONFIG] ?? null;
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
