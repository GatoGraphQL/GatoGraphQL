<?php
use PoP\ComponentModel\Facades\Engine\EngineFacade;
use PoP\ComponentModel\Facades\HelperServices\RequestHelperServiceFacade;
use PoP\ComponentModel\Facades\Info\ApplicationInfoFacade;
use PoP\Definitions\Constants\Params as DefinitionsParams;
use PoP\Definitions\Constants\ParamValues as DefinitionsParamValues;
use PoPCMSSchema\SchemaCommons\Facades\CMS\CMSServiceFacade;
use PoP\LooseContracts\Facades\NameResolverFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\CustomPosts\Types\Status;

define('POP_HOOK_POPWEBPLATFORM_KEEPOPENTABS', 'popwebplatform-keepopentabs');

class PoPWebPlatform_Initialization
{
    protected $scripts;

    public function __construct()
    {
        $this->scripts = array();
    }

    public function initialize()
    {
        load_plugin_textdomain('pop-engine-webplatform', false, dirname(plugin_basename(__FILE__)).'/languages');

        /**
         * Constants/Configuration for functionalities needed by the plug-in
         */
        require_once 'config/load.php';

        /**
         * Load the Contracts
         */
        include_once 'contracts/load.php';

        /**
         * Kernel
         */
        require_once 'kernel/load.php';

        /**
         * Load the Library first
         */
        require_once 'library/load.php';
        require_once 'platforms/load.php';

        // If it is a search engine, there's no need to output the scripts or initialize pop.Manager
        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        if (!$cmsapplicationapi->isAdminPanel()/* && !RequestUtils::isSearchEngine()*/) {
            \PoP\Root\App::addAction('popcms:enqueueScripts', array($this, 'registerScripts'));

            // Print all jQuery functions, execute after all the plugin scripts have loaded
            // Load before we start printing the footer scripts, so we can add the 'after' data to the required scripts
            \PoP\Root\App::addAction('popcms:printFooterScripts', array($this, 'initScripts'), 0);
            \PoP\Root\App::addAction('popcms:printFooterScripts', array($this, 'printScripts'), PHP_INT_MAX);
        }
    }

    public function registerScripts()
    {
        // Only if not doing code splitting then load the resources. Otherwise, the resources will be loaded by the ResourceLoader
        if (PoP_WebPlatform_ConfigurationUtils::registerScriptsAndStylesDuringInit()) {
            $cmswebplatformapi = \PoP\EngineWebPlatform\FunctionAPIFactory::getInstance();
            $js_folder = POP_ENGINEWEBPLATFORM_URL.'/js';
            $dist_js_folder = $js_folder.'/dist';
            $libraries_js_folder = (PoP_WebPlatform_ServerUtils::useMinifiedResources() ? $dist_js_folder : $js_folder).'/libraries';
            $suffix = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? '.min' : '';
            $bundles_js_folder = $dist_js_folder.'/bundles';
            $includes_js_folder = $js_folder.'/includes';
            $cdn_js_folder = $includes_js_folder . '/cdn';

            if (PoP_WebPlatform_ServerUtils::accessExternalcdnResources()) {

                // http://handlebarsjs.com/installation.html
                // // Comment Leo: Version 4.0.10 has a bug (https://github.com/wycats/handlebars.js/issues/1300) that make the application not work correctly
                $cmswebplatformapi->registerScript('handlebars', 'https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.10/handlebars.runtime.min.js', null, null);
                // $cmswebplatformapi->registerScript('handlebars', 'https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.5/handlebars.runtime.min.js', null, null);
            } else {

                // // Comment Leo: Version 4.0.10 has a bug (https://github.com/wycats/handlebars.js/issues/1300) that make the application not work correctly
                $cmswebplatformapi->registerScript('handlebars', $cdn_js_folder . '/handlebars.runtime.4.0.10.min.js', null, null);
            }
            $cmswebplatformapi->enqueueScript('handlebars');

            $localize_handle = 'pop';
            if (PoP_WebPlatform_ServerUtils::useBundledResources()) {
                $cmswebplatformapi->registerScript('pop-engine-webplatform-templates', $bundles_js_folder . '/pop-engine.templates.bundle.min.js', array(), POP_ENGINEHTMLCSSPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-engine-webplatform-templates');

                $cmswebplatformapi->registerScript('pop', $bundles_js_folder . '/pop-engine.bundle.min.js', array('jquery', 'jquery-ui-sortable'), POP_ENGINEWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop');
            } else {

                // First script from below
                $localize_handle = 'pop-helpers-handlebars-kernel';

                // Handlebars Helpers
                $cmswebplatformapi->registerScript('pop-helpers-handlebars-kernel', $libraries_js_folder.'/handlebars-helpers/kernel'.$suffix.'.js', array('handlebars'), POP_ENGINEWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-helpers-handlebars-kernel');

                // Scripts
                $cmswebplatformapi->registerScript('pop-utils-functions', $libraries_js_folder.'/utils'.$suffix.'.js', array('jquery'), POP_ENGINEWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-utils-functions');

                $cmswebplatformapi->registerScript('pop-utils', $libraries_js_folder.'/pop-utils'.$suffix.'.js', array('jquery'), POP_ENGINEWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-utils');

                $cmswebplatformapi->registerScript('pop-compatibility', $libraries_js_folder.'/compatibility'.$suffix.'.js', array('jquery'), POP_ENGINEWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-compatibility');

                $cmswebplatformapi->registerScript('pop-jslibrary-manager', $libraries_js_folder.'/jslibrary-manager'.$suffix.'.js', array('jquery'), POP_ENGINEWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-jslibrary-manager');

                $cmswebplatformapi->registerScript('pop-jsruntime-manager', $libraries_js_folder.'/jsruntime-manager'.$suffix.'.js', array('jquery', 'pop-jslibrary-manager'), POP_ENGINEWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-jsruntime-manager');

                $cmswebplatformapi->registerScript('pop-pagesection-manager', $libraries_js_folder.'/pagesection-manager'.$suffix.'.js', array('jquery', 'pop-jslibrary-manager'), POP_ENGINEWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-pagesection-manager');

                $cmswebplatformapi->registerScript('pop-history', $libraries_js_folder.'/history'.$suffix.'.js', array('jquery', 'pop-jslibrary-manager', 'pop-jsruntime-manager'), POP_ENGINEWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-history');

                $cmswebplatformapi->registerScript('pop-interceptors', $libraries_js_folder.'/interceptors'.$suffix.'.js', array('jquery', 'pop-jslibrary-manager'), POP_ENGINEWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-interceptors');

                $cmswebplatformapi->registerScript('pop-lifecycle', $libraries_js_folder.'/lifecycle'.$suffix.'.js', array('jquery'), POP_ENGINEWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-lifecycle');

                $cmswebplatformapi->registerScript('pop-data-store', $libraries_js_folder.'/data-store'.$suffix.'.js', array('jquery'), POP_ENGINEWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop-data-store');

                // Sortable needed for the Typeahead
                $cmswebplatformapi->registerScript('pop', $libraries_js_folder.'/pop-manager'.$suffix.'.js', array('jquery', 'pop-utils', 'pop-pagesection-manager', 'pop-history', 'pop-interceptors', 'pop-jslibrary-manager', 'pop-jsruntime-manager', 'pop-data-store', 'jquery-ui-sortable'), POP_ENGINEWEBPLATFORM_VERSION, true);
                $cmswebplatformapi->enqueueScript('pop');

                /** Templates */
                $this->enqueueTemplatesScripts();
            }

            // Print all jQuery functions constants
            $jqueryConstants = $this->getJqueryConstants();
            $cmswebplatformapi->localizeScript($localize_handle, 'pop', array('c' => $jqueryConstants));
        }
    }

    public function enqueueTemplatesScripts()
    {
        $cmswebplatformapi = \PoP\EngineWebPlatform\FunctionAPIFactory::getInstance();
        $folder = POP_ENGINEWEBPLATFORM_URL.'/js/dist/templates/';

        $cmswebplatformapi->enqueueScript('extension-appendableclass-tmpl', $folder.'extension-appendableclass.tmpl.js', array('handlebars'), POP_ENGINEHTMLCSSPLATFORM_VERSION, true);
    }

    public function getJqueryConstants()
    {
        $cmsService = CMSServiceFacade::getInstance();

        // Define all jQuery constants
        //---------------------------------------------------------------------------------
        $ondate = sprintf(
            PoP_HTMLCSSPlatform_ConfigurationUtils::getOndateString(),
            '{0}'
        );

        $homeurl = $cmsService->getSiteURL();
        $allowed_domains = PoP_WebPlatform_ConfigurationUtils::getAllowedDomains();

        // Locale is needed to store the Open Tabs under the right language
        $locale = \PoP\Root\App::applyFilters('pop_modulemanager:locale', get_locale());

        // Default one: do not send, so that it doesn't show up in the Embed URL
        $keepopentabs = \PoP\Root\App::applyFilters(POP_HOOK_POPWEBPLATFORM_KEEPOPENTABS, true);
        $multilayout_labels = PoP_HTMLCSSPlatform_ConfigurationUtils::getMultilayoutLabels();
        // $multilayout_keyfields = PoP_WebPlatform_ConfigurationUtils::get_multilayout_keyfields();
        $domcontainer_id = \PoP\Root\App::applyFilters('pop_modulemanager:domcontainer_id', POP_MODULEID_PAGESECTIONCONTAINERID_CONTAINER);
        $addanchorspinner = \PoP\Root\App::applyFilters('pop_modulemanager:add_anchor_spinner', true);
        $api_urlparams = \PoP\Root\App::applyFilters('pop_modulemanager:api_urlparams', array(
            \PoP\ComponentModel\Constants\Params::OUTPUT => \PoP\ComponentModel\Constants\Outputs::JSON,
            \PoP\ComponentModel\Constants\Params::DATA_OUTPUT_ITEMS => array(
                \PoP\ComponentModel\Constants\DataOutputItems::META,
                \PoP\ComponentModel\Constants\DataOutputItems::MODULE_DATA,
                \PoP\ComponentModel\Constants\DataOutputItems::DATABASES,
            ),
            DefinitionsParams::MANGLED => DefinitionsParamValues::MANGLED_NONE,
        ));

        $requestHelperService = RequestHelperServiceFacade::getInstance();
        $jqueryConstants = array(
            'INITIAL_URL' => $requestHelperService->getCurrentURL(), // Needed to always identify which was the first URL loaded
            'HOME_DOMAIN' => $homeurl,
            'ALLOWED_DOMAINS' => $allowed_domains,
            'VERSION' => ApplicationInfoFacade::getInstance()->getVersion(),
            'LOCALE' => $locale,
            'API_URLPARAMS' => $api_urlparams,
            'USE_PROGRESSIVEBOOTING' => (PoP_WebPlatform_ServerUtils::useProgressiveBooting() ? true : ''),
            'COMPACT_RESPONSE_JSON_KEYS' => (\PoP\ComponentModel\Environment::compactResponseJsonKeys() ? true : ''),
            'USELOCALSTORAGE' => (PoP_WebPlatform_ServerUtils::useLocalStorage() ? true : ''),
            // This URL is needed to retrieve the user data, if the user is logged in
            // 'BACKGROUND_LOAD' => $backgroundLoad,
            'KEEP_OPEN_TABS' => $keepopentabs ? true : '',
            'USERLOGGEDIN_LOADINGMSG_TARGET' => \PoP\Root\App::applyFilters('pop_modulemanager:userloggedin_loadingmsg_target', null),
            // Define variable below to be overriden by WP Super Cache (if plugin disabled, it won't break anything)
            'AJAXURL' => admin_url('admin-ajax.php', 'relative'),
            'UPLOADURL' => admin_url('async-upload.php', 'relative'),
            'GMT_OFFSET' => $cmsService->getOption(NameResolverFacade::getInstance()->getName('popcms:option:gmtOffset')),
            'DATAOUTPUTMODE' => \PoP\Root\App::getState('dataoutputmode'),
            'DBOUTPUTMODE' => \PoP\Root\App::getState('dboutputmode'),
            'ERROR_MESSAGE' => '<div class="alert alert-danger alert-block fade in"><button type="button" class="close" data-dismiss="alert">x</button>{0}</div>',
            'POSTSTATUS' => array(
                'PUBLISH' => Status::PUBLISHED,
                'PENDING' => Status::PENDING,
            ),
            'STATUS' => PoP_HTMLCSSPlatform_ConfigurationUtils::getStatusSettings(),
            'LABELIZE_CLASSES' => PoP_HTMLCSSPlatform_ConfigurationUtils::getLabelizeClasses(),
            'LABELS' => array(
                'DOWNLOAD' => TranslationAPIFacade::getInstance()->__('Download', 'pop-engine-webplatform'),
                'MEDIA_FEATUREDIMAGE_TITLE' => TranslationAPIFacade::getInstance()->__('Set Featured Image', 'pop-engine-webplatform'),
                'MEDIA_FEATUREDIMAGE_BTN' => TranslationAPIFacade::getInstance()->__('Set', 'pop-engine-webplatform'),
            ),
            'FETCHTARGET_SETTINGS' => \PoP\Root\App::applyFilters('pop_modulemanager:fetchtarget_settings', array()),
            'FETCHPAGESECTION_SETTINGS' => \PoP\Root\App::applyFilters('pop_modulemanager:fetchpagesection_settings', array()),
            'MULTILAYOUT_LABELS' => $multilayout_labels,
            // 'MULTILAYOUT_KEYFIELDS' => $multilayout_keyfields,
            'ADDANCHORSPINNER' => $addanchorspinner,
            'STRING_MORE' => GD_STRING_MORE,
            'STRING_LESS' => GD_STRING_LESS,
            'ONDATE' => $ondate,
            'PATHSTARTPOS' => \PoP\Root\App::applyFilters('pop_modulemanager:pathstartpos', 1),
            'THROW_EXCEPTION_ON_TEMPLATE_ERROR' => (PoP_HTMLCSSPlatform_ServerUtils::throwExceptionOnTemplateError() ? true : ''),
        );

        // Allow qTrans to add the language information
        if ($homelocaleurl = \PoP\Root\App::applyFilters('pop_modulemanager:homelocale_url', $homeurl)) {
            $jqueryConstants['HOMELOCALE_URL'] = $homelocaleurl;
        }

        if ($domcontainer_id) {
            $jqueryConstants['DOMCONTAINER_ID'] = $domcontainer_id;
        }

        return \PoP\Root\App::applyFilters('gd_jquery_constants', $jqueryConstants);
    }

    public function initScripts()
    {

        // When embedding a post using oEmbed, it creates the post url + /embed/ at the end, however
        // the scripts are not loaded, so doing pop.Manager.init(); fails and gives a JS error
        // So do nothing when this post is an embed
        if (is_embed()) {
            return;
        }

        $cmsService = CMSServiceFacade::getInstance();

        // Allow PoP Server-Side Rendering, PoP Resource Loader to add their scripts
        $this->scripts = \PoP\Root\App::applyFilters(
            'PoPWebPlatform_Initialization:init-scripts',
            $this->scripts
        );

        // Set the Data object
        $engine = EngineFacade::getInstance();
        $this->scripts[] = sprintf(
            'window.pop.Data = %s;',
            json_encode(array(
                $cmsService->getSiteURL() => $engine->getOutputData(),
            ))
        );

        // Add a hook to fill the settings values from pop-runtimecontent .js files
        $this->scripts[] = '$(document).triggerHandler(\'PoP:initData\', [window.pop.Data]);';

        // At the end, execute the code initializing everything
        $this->scripts[] = 'pop.Manager.init();';
    }

    public function printScripts()
    {
        if (!PoP_WebPlatform_ServerUtils::disableJs()) {
            if ($this->scripts) {
                printf(
                    '<script type="text/javascript">%s</script>',
                    implode(PHP_EOL, $this->scripts)
                );
            }
        }
    }
}

/**
 * Initialization
 */
global $PoPWebPlatform_Initialization;
$PoPWebPlatform_Initialization = new PoPWebPlatform_Initialization();
