<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

define('GD_CONSTANT_ERROR_MSG', '<i class="fa fa-fw fa-warning"></i>'.TranslationAPIFacade::getInstance()->__('Oops, there was a connection problem.', 'pop-coreprocessors'));
define('GD_CONSTANT_OFFLINE_MSG', '<i class="fa fa-fw fa-warning"></i>'.TranslationAPIFacade::getInstance()->__('It seems you are offline.', 'pop-coreprocessors'));
define('GD_CONSTANT_RETRY_MSG', TranslationAPIFacade::getInstance()->__('Retry', 'pop-coreprocessors'));
define('GD_CONSTANT_AUTHORS_SEPARATOR', '<span class="preview-author-sep">|</span>');
define('GD_CONSTANT_MANDATORY', '*');
define('GD_SEPARATOR', ',');
define('GD_CONSTANT_FEEDBACKMSG_MULTIDOMAIN', TranslationAPIFacade::getInstance()->__('(<em>{0}</em>) {1}', 'pop-coreprocessors'));

define('GD_SETTINGS_PARAMSSCOPE_URL', 'url');

define('POP_MODULEID_PAGESECTIONGROUP_ID', 'pagesection-group');
define('POP_MODULEID_QUICKVIEWPAGESECTIONGROUP_ID', 'quickviewpagesection-group');

// wpEditor
define('GD_MODULESETTINGS_EDITOR_NAME', 'editorcomponent');

// define ('GD_INTERCEPTOR_WITHPARAMS', 'with-params');
define('GD_JS_INITIALIZED', 'js-initialized');
define('GD_CRITICALJS_INITIALIZED', 'criticaljs-initialized');

// define ('GD_MODULECALLBACK_ACTION_LOADCONTENT', 'loadcontent');
// define ('GD_MODULECALLBACK_ACTION_REFETCH', 'refetch');
// define ('GD_MODULECALLBACK_ACTION_RESET', 'reset');

define('GD_JSPLACEHOLDER_QUERY', '*QUERY*'); // Replaced from '%QUERY' because using '%' gives a JS error (Uncaught URIError: URI malformed) on function splitParams in utils.js when trying to add yet another parameter on that URL

define('POP_KEYS_THUMBPRINT', 'thumbprint');

\PoP\Root\App::getHookManager()->addFilter('gd_jquery_constants', 'gdPopcoreJqueryConstantsModulemanagerImpl');
function gdPopcoreJqueryConstantsModulemanagerImpl($jqueryConstants)
{
    $jqueryConstants['JSPLACEHOLDER_QUERY'] = GD_JSPLACEHOLDER_QUERY;

    // ------------------------------------------
    // Constants from PoP (plugins/pop/kernel/library/constants.php)
    // ------------------------------------------
    $jqueryConstants['ID_SEPARATOR'] = POP_CONSTANT_ID_SEPARATOR;
    // $jqueryConstants['ID_JSON'] = POP_ID_JSON;
    $jqueryConstants['MODULESETTINGS_ENTRYMODULE'] = "must-remove-this-constant";//POP_MODULESETTINGS_ENTRYMODULE;
    $jqueryConstants['JSMETHOD_GROUP_MAIN'] = GD_JSMETHOD_GROUP_MAIN;
    // ------------------------------------------

    $jqueryConstants['SETTINGS_PARAMSSCOPE_URL'] = GD_SETTINGS_PARAMSSCOPE_URL;

    $jqueryConstants['MODULEID_PAGESECTIONGROUP_ID'] = POP_MODULEID_PAGESECTIONGROUP_ID;

    // wpEditor
    $jqueryConstants['MODULESETTINGS_EDITOR_NAME'] = GD_MODULESETTINGS_EDITOR_NAME;

    $jqueryConstants['SEPARATOR'] = GD_SEPARATOR;

    // $jqueryConstants['INTERCEPTOR_WITHPARAMS'] = GD_INTERCEPTOR_WITHPARAMS;
    $jqueryConstants['JS_INITIALIZED'] = GD_JS_INITIALIZED;
    $jqueryConstants['CRITICALJS_INITIALIZED'] = GD_CRITICALJS_INITIALIZED;

    // $jqueryConstants['CBACTION_LOADCONTENT'] = GD_MODULECALLBACK_ACTION_LOADCONTENT;
    // $jqueryConstants['CBACTION_REFETCH'] = GD_MODULECALLBACK_ACTION_REFETCH;
    // $jqueryConstants['CBACTION_RESET'] = GD_MODULECALLBACK_ACTION_RESET;

    $jqueryConstants['ERROR_MSG'] = sprintf(
        '%s <a href="{0}" target="{1}">%s</a>',
        GD_CONSTANT_ERROR_MSG,
        GD_CONSTANT_RETRY_MSG
    );
    $jqueryConstants['ERROR_OFFLINE'] = sprintf(
        '%s <a href="{0}" target="{1}">%s</a>',
        GD_CONSTANT_OFFLINE_MSG,
        GD_CONSTANT_RETRY_MSG
    );
    $jqueryConstants['ERROR_404'] = TranslationAPIFacade::getInstance()->__('Oops, this is a broken link.', 'pop-coreprocessors');
    $jqueryConstants['FEEDBACKMSG_MULTIDOMAIN'] = GD_CONSTANT_FEEDBACKMSG_MULTIDOMAIN;

    $jqueryConstants['KEYS_THUMBPRINT'] = POP_KEYS_THUMBPRINT;

    return $jqueryConstants;
}

\PoP\Root\App::getHookManager()->addFilter('gd_hack:script_loader:default_error', 'gdWpScriptLoaderDefaultError');
function gdWpScriptLoaderDefaultError($error)
{
    return TranslationAPIFacade::getInstance()->__('Oops, the upload failed. Let\'s fix this: please save your post as \'Draft\', refresh the browser window, and try again.', 'pop-coreprocessors');
}
