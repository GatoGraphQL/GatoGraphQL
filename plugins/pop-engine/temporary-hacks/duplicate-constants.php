<?php

// These constants are defined at PoP FrontendEngine level. When this plugin is not enabled, the constants still show up in the JSON. To make it nicer, duplicate them for the time being
\PoP\CMS\HooksAPI_Factory::getInstance()->addAction('plugins_loaded', 'popEngineDuplicateConstants', 101);
function popEngineDuplicateConstants()
{
    define('GD_CONSTANT_LOADING_MSG', __('Loading', 'pop-coreprocessors'));
    define('GD_CONSTANT_LOADING_SPINNER', '<i class="fa fa-fw fa-spinner fa-spin"></i>');
    define('POP_LOADING_MSG', GD_CONSTANT_LOADING_SPINNER.' '.GD_CONSTANT_LOADING_MSG);

    $compact = \PoP\Engine\Server\Utils::compactResponseJsonKeys();
    define('GD_JS_TEMPLATE', $compact ? 't' : 'template');
    define('GD_JS_MODULENAMES', $compact ? 'ti' : 'module-names');
    define('GD_JS_SETTINGSIDS', $compact ? 'ss' : 'settings-ids');
    define('POP_JS_TEMPLATES', $compact ? 'tx' : 'templates');
    define('GD_JS_METHODS', $compact ? 'mt' : 'methods');
    define('GD_JS_NEXT', $compact ? 'n' : 'next');
    define('GD_JS_FIXEDID', $compact ? 'fx' : 'fixed-id');
    define('GD_JS_ISIDUNIQUE', $compact ? 'iu' : 'is-id-unique');
    define('GD_JS_FRONTENDID', $compact ? 'f' : 'frontend-id');
    define('GD_JS_INTERCEPTSKIPSTATEUPDATE', $compact ? 'is' : 'intercept-skipstateupdate');
    define('GD_JS_APPENDABLE', $compact ? 'ap' : 'appendable');
    define('GD_JS_CLASS', $compact ? 'c' : 'class');
    define('GD_JS_CLASSES', $compact ? 'cs' : 'classes');
    define('GD_JS_STYLE', $compact ? 'y' : 'style');
    define('GD_JS_STYLES', $compact ? 'ys' : 'styles');
    define('GD_JS_TITLES', $compact ? 'tt' : 'titles');
    define('GD_JS_PARAMS', $compact ? 'p' : 'params');
    define('GD_JS_DBOBJECTPARAMS', $compact ? 'dop' : 'dbobject-params');
    define('GD_JS_PREVIOUSMODULESIDS', $compact ? 'pt' : 'previousmodules-ids');
    define('GD_JS_DESCRIPTION', $compact ? 'd' : 'description');
    define('GD_JS_INTERCEPTURLS', $compact ? 'xu' : 'intercept-urls');
    define('GD_JS_EXTRAINTERCEPTURLS', $compact ? 'exu' : 'extra-intercept-urls');
}
