<?php
use PoP\ComponentModel\ComponentInfo as ComponentModelComponentInfo;


$compact = \PoP\ComponentModel\Environment::compactResponseJsonKeys();
define('GD_JS_MODULE', $compact ? 'm' : 'module');
define('GD_JS_MODULEOUTPUTNAME', $compact ? 's' : 'moduleoutputname');
define('GD_JS_INTERCEPTURLS', $compact ? 'xu' : 'intercept-urls');
define('GD_JS_EXTRAINTERCEPTURLS', $compact ? 'exu' : 'extra-intercept-urls');

// Intercept
define('GD_JS_INTERCEPT', $compact ? 'i' : 'intercept');
define('GD_JS_TYPE', $compact ? 'ty' : 'type');
define('GD_JS_SETTINGS', $compact ? 'st' : 'settings');
define('GD_JS_TARGET', $compact ? 'tg' : 'target');
define('GD_JS_SKIPSTATEUPDATE', $compact ? 'sk' : 'skipstateupdate');

\PoP\Root\App::addFilter('gd_jquery_constants', gdJqueryConstantsJsparams(...));
function gdJqueryConstantsJsparams($jqueryConstants)
{

    // From PoP Engine
    $jqueryConstants['JS_MODULE'] = GD_JS_MODULE;
    $jqueryConstants['JS_SUBMODULES'] = ComponentModelComponentInfo::get('response-prop-submodules');
    $jqueryConstants['JS_MODULEOUTPUTNAME'] = GD_JS_MODULEOUTPUTNAME;

    // From Web Platform PoP Engine
    $jqueryConstants['JS_TEMPLATE'] = GD_JS_TEMPLATE;
    $jqueryConstants['JS_SUBMODULEOUTPUTNAMES'] = GD_JS_SUBMODULEOUTPUTNAMES;
    $jqueryConstants['JS_TEMPLATES'] = POP_JS_TEMPLATES;
    $jqueryConstants['JS_METHODS'] = GD_JS_METHODS;
    $jqueryConstants['JS_NEXT'] = GD_JS_NEXT;
    $jqueryConstants['JS_FIXEDID'] = GD_JS_FIXEDID;
    $jqueryConstants['JS_ISIDUNIQUE'] = GD_JS_ISIDUNIQUE;
    $jqueryConstants['JS_FRONTENDID'] = GD_JS_FRONTENDID;
    $jqueryConstants['JS_INTERCEPTSKIPSTATEUPDATE'] = GD_JS_INTERCEPTSKIPSTATEUPDATE;
    $jqueryConstants['JS_APPENDABLE'] = GD_JS_APPENDABLE;
    $jqueryConstants['JS_CLASS'] = GD_JS_CLASS;
    $jqueryConstants['JS_CLASSES'] = GD_JS_CLASSES;
    $jqueryConstants['JS_STYLE'] = GD_JS_STYLE;
    $jqueryConstants['JS_STYLES'] = GD_JS_STYLES;
    $jqueryConstants['JS_TITLES'] = GD_JS_TITLES;
    $jqueryConstants['JS_PARAMS'] = GD_JS_PARAMS;
    $jqueryConstants['JS_DBOBJECTPARAMS'] = GD_JS_DBOBJECTPARAMS;
    $jqueryConstants['JS_PREVIOUSMODULESIDS'] = GD_JS_PREVIOUSMODULESIDS;
    $jqueryConstants['JS_INTERCEPT'] = GD_JS_INTERCEPT;
    $jqueryConstants['JS_TYPE'] = GD_JS_TYPE;
    $jqueryConstants['JS_SETTINGS'] = GD_JS_SETTINGS;
    $jqueryConstants['JS_TARGET'] = GD_JS_TARGET;
    $jqueryConstants['JS_SKIPSTATEUPDATE'] = GD_JS_SKIPSTATEUPDATE;
    $jqueryConstants['JS_DESCRIPTION'] = GD_JS_DESCRIPTION;
    $jqueryConstants['JS_INTERCEPTURLS'] = GD_JS_INTERCEPTURLS;
    $jqueryConstants['JS_EXTRAINTERCEPTURLS'] = GD_JS_EXTRAINTERCEPTURLS;

    return $jqueryConstants;
}
