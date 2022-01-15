<?php
use PoP\ComponentModel\ComponentInfo as ComponentModelComponentInfo;
use PoP\ComponentModel\Facades\HelperServices\RequestHelperServiceFacade;
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\Misc\RequestUtils;
use PoPSchema\SchemaCommons\Facades\CMS\CMSServiceFacade;

class PoP_ServerSideManager
{
    public function __construct()
    {
        PoP_ServerSide_LibrariesFactory::setPopmanagerInstance($this);
    }

    //-------------------------------------------------
    // PUBLIC but NOT EXPOSED functions
    //-------------------------------------------------

    public function &getStoreData($domain)
    {
        $datastore = PoP_ServerSide_LibrariesFactory::getDatastoreInstance();
        $memory =& $datastore->store[$domain] ?? array();
        return $memory;
    }

    public function &getStatelessData($domain)
    {
        $datastore = PoP_ServerSide_LibrariesFactory::getDatastoreInstance();
        $stateless =& $this->getStoreData($domain)['statelessdata'] ?? array();
        return $stateless;
    }

    public function &getStatefulData($domain, $url)
    {
        $datastore = PoP_ServerSide_LibrariesFactory::getDatastoreInstance();
        $mutableonrequest =& $this->getStoreData($domain)['mutableonrequestdata'][$url] ?? array();
        return $mutableonrequest;
    }

    public function &getCombinedStateData($domain, $url)
    {
        $datastore = PoP_ServerSide_LibrariesFactory::getDatastoreInstance();
        $combinedstate =& $this->getStoreData($domain)['combinedstatedata'][$url] ?? array();
        return $combinedstate;
    }

    public function &getDatabases($domain)
    {
        $datastore = PoP_ServerSide_LibrariesFactory::getDatastoreInstance();
        $databases =& $datastore->store[$domain]['dbData'] ?? array();
        return $databases;
    }

    public function &getPrimaryDatabase($domain)
    {
        $datastore = PoP_ServerSide_LibrariesFactory::getDatastoreInstance();
        $primarydatabase =& $this->getDatabases($domain)['primary'] ?? array();
        return $primarydatabase;
    }

    public function &getUserStateDatabase($domain)
    {
        $datastore = PoP_ServerSide_LibrariesFactory::getDatastoreInstance();
        $userstatedatabase =& $this->getDatabases($domain)['userstate'] ?? array();
        return $userstatedatabase;
    }

    public function &getTemplates($domain)
    {
        $datastore = PoP_ServerSide_LibrariesFactory::getDatastoreInstance();
        $templates =& $datastore->store[$domain]['modulesettings']['combinedstate']['templates'] ?? array();
        return $templates;
    }

    // Comment Leo: passing extra parameter $json in PHP
    public function initTopLevelJson($domain, $json)
    {
        $datastore = PoP_ServerSide_LibrariesFactory::getDatastoreInstance();

        // Databases and stateless data can be integrated straight
        $datastore->store[$domain] = $datastore->store[$domain] ?? array();
        $datastore->store[$domain]['dbData'] = $json['dbData'];
        $datastore->store[$domain]['statelessdata'] = $json['statelessdata'];

        // Stateful data is to be integrated under the corresponding URL
        $requestHelperService = RequestHelperServiceFacade::getInstance();
        $url = $requestHelperService->getCurrentURL();
        $datastore->store[$domain]['mutableonrequestdata'] = array(
            $url => $json['moduledata']['mutableonrequest'],
        );
        $datastore->store[$domain]['combinedstatedata'] = array(
            $url => $json['moduledata']['combinedstate'],
        );
    }

    // ------------------------------------------------------
    // Comment Leo: Heavily commented in PHP
    // Comment Leo: passing extra parameter $json in PHP
    // ------------------------------------------------------
    public function init($json)
    {
        $datastore = PoP_ServerSide_LibrariesFactory::getDatastoreInstance();
        // $datastore->init($json);
        $cmsService = CMSServiceFacade::getInstance();
        foreach (array($cmsService->getSiteURL())/*PoP_WebPlatform_ConfigurationUtils::getAllowedDomains()*/ as $domain) {
            $datastore->store[$domain] = array();
            //     $datastore->store[$domain] = array(
            //         'statelessdata' => array(
            //             'settings' => array(
            //                 'configuration' => array(),
            //                 'js-settings' => array(),
            //                 'jsmethods' => array(
            //                     'pagesection' => array(),
            //                     'block' => array(),
            //                 ),
            //                 'modules-cbs' => array(),
            //                 'modules-paths' => array(),
            //                 'db-keys' => array(),
            //             ),
            //         ),
            //         'mutableonrequestdata' => array(
            //             'settings' => array(
            //                 'configuration' => array(),
            //                 'js-settings' => array(),
            //             ),
            //             'dbobjectids' => array(),
            //             'feedback' => array(
            //                 'block' => array(),
            //                 'pagesection' => array(),
            //                 'toplevel' => array(),
            //             ),
            //             'querystate' => array(
            //                 'sharedbydomains' => array(),
            //                 'uniquetodomain' => array(),
            //             ),
            //         ),
            //         'dbData' => array(
            //             'primary' => array(),
            //             'userstate' => array(),
            //         ),
            //     );
        }

        $domain = $cmsService->getSiteURL();

        // Initialize Settings, Feedback and Data
        // Comment Leo: passing extra parameter $json in PHP
        $this->initTopLevelJson($domain, $json);

        // Set the URL for the 'session-ids'
        $popJSRuntimeManager = PoP_ServerSide_LibrariesFactory::getJsruntimeInstance();
        $requestHelperService = RequestHelperServiceFacade::getInstance();
        $url = $requestHelperService->getCurrentURL();
        $popJSRuntimeManager->setPageSectionURL($url);

        // Step 0: initialize the pageSection
        foreach ($this->getCombinedStateData($domain, $url)['settings']['configuration'] as $pssId => &$psConfiguration) {
            $this->initPageSectionSettings($domain, $pssId, $psConfiguration); // Changed line in PHP, different in JS
            // Insert into the Runtime to generate the ID
            $this->addPageSectionIds($domain, $pssId, $psConfiguration[GD_JS_MODULE]); // Changed line in PHP, different in JS
        }
    }

    public function isFirstLoad($pageSection)
    {

        // ------------------------------------------------------
        // Comment Leo: rendering html in server-side is always firstLoad
        // ------------------------------------------------------
        // return $this->$firstLoad[$popManager->getSettingsId($pageSection)];
        return true;
    }

    public function expandJSKeys(&$context)
    {

        // In order to save file size, context keys can be compressed, eg: 'modules' => 'ms', 'module' => 'm'. However they might be referenced with their full name
        // in .tmpl files, so reconstruct the full name in the context duplicating these entries
        if ($context && \PoP\ComponentModel\Environment::compactResponseJsonKeys()) {
            // Hardcoding always 'modules' allows us to reference this key, with certainty of its name, in the .tmpl files
            if ($context[ComponentModelComponentInfo::get('response-prop-submodules')] ?? null) {
                $context['modules'] = $context[ComponentModelComponentInfo::get('response-prop-submodules')];
            }
            if ($context['bs']['dbkeys'] ?? null) {
                $context['bs']['dbkeys'] = $context['bs']['dbkeys'];
            }
            if ($context[GD_JS_MODULE] ?? null) {
                $context['module'] = $context[GD_JS_MODULE];
            }
            if ($context[GD_JS_TEMPLATE] ?? null) {
                $context['template'] = $context[GD_JS_TEMPLATE];
            }
            if ($context[GD_JS_MODULEOUTPUTNAME] ?? null) {
                $context['moduleoutputname'] = $context[GD_JS_MODULEOUTPUTNAME];
            }
            if ($context[GD_JS_SUBMODULEOUTPUTNAMES] ?? null) {
                $context['submoduleoutputnames'] = $context[GD_JS_SUBMODULEOUTPUTNAMES];
            }
            if ($context[POP_JS_TEMPLATES] ?? null) {
                $context['templates'] = $context[POP_JS_TEMPLATES];
            }
            if ($context[GD_JS_INTERCEPT] ?? null) {
                $context['intercept'] = $context[GD_JS_INTERCEPT];
            }

            // Params
            if ($context[GD_JS_PARAMS] ?? null) {
                $context['params'] = $context[GD_JS_PARAMS];
            }
            if ($context[GD_JS_DBOBJECTPARAMS] ?? null) {
                $context['dbobject-params'] = $context[GD_JS_DBOBJECTPARAMS];
            }
            if ($context[GD_JS_PREVIOUSMODULESIDS] ?? null) {
                $context['previousmodules-ids'] = $context[GD_JS_PREVIOUSMODULESIDS];
            }

            // Appendable
            if ($context[GD_JS_APPENDABLE] ?? null) {
                $context['appendable'] = $context[GD_JS_APPENDABLE];
            }

            // Frequently used keys in many different modules
            if ($context[GD_JS_CLASS] ?? null) {
                $context['class'] = $context[GD_JS_CLASS];
            }
            if ($context[GD_JS_CLASSES] ?? null) {
                $context['classes'] = $context[GD_JS_CLASSES];

                if ($context[GD_JS_CLASSES][GD_JS_APPENDABLE] ?? null) {
                    $context['classes']['appendable'] = $context[GD_JS_CLASSES][GD_JS_APPENDABLE];
                }
            }
            if ($context[GD_JS_STYLE] ?? null) {
                $context['style'] = $context[GD_JS_STYLE];
            }
            if ($context[GD_JS_STYLES] ?? null) {
                $context['styles'] = $context[GD_JS_STYLES];
            }
            if ($context[GD_JS_TITLES] ?? null) {
                $context['titles'] = $context[GD_JS_TITLES];
            }
            if ($context[GD_JS_DESCRIPTION] ?? null) {
                $context['description'] = $context[GD_JS_DESCRIPTION];
            }
            if ($context[GD_JS_INTERCEPTURLS] ?? null) {
                $context['intercept-urls'] = $context[GD_JS_INTERCEPTURLS];

                if ($context[GD_JS_EXTRAINTERCEPTURLS] ?? null) {
                    $context['extra-intercept-urls'] = $context[GD_JS_EXTRAINTERCEPTURLS];
                }
            }

            // Allow Custom .js to add their own JS Keys (eg: Fontawesome)
            $popJSLibraryManager = PoP_ServerSide_LibrariesFactory::getJslibraryInstance();
            $args = array('context' => &$context);
            $popJSLibraryManager->execute('expandJSKeys', $args);
        }
    }

    public function addPageSectionIds($domain, $pageSection, $moduleName)
    {
        $pssId = $this->getSettingsId($pageSection);

        // Comment Leo 19/10/2017: obviously we can't get the $psId from its HTML attr, so just get it from the corresponding processor
        // We need to provide the proper $props, however just to get the pageSection id it works without $props too, so just pass array()
        // (this is hacky, but well, it works)
        // $psId = pageSection.attr('id');
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
        $props = array();
        $psId = $moduleprocessor_manager->getProcessor($moduleName)->getID($moduleName, $props);

        // Insert into the Runtime to generate the ID
        $popJSRuntimeManager = PoP_ServerSide_LibrariesFactory::getJsruntimeInstance();
        $popJSRuntimeManager->addPageSectionId($domain, $domain, $pssId, $moduleName, $psId);

        $args = array(
            'domain' => $domain,
            'pageSection' => $pageSection,
            'module' => $moduleName,
        );

        $popJSLibraryManager = PoP_ServerSide_LibrariesFactory::getJslibraryInstance();
        $popJSLibraryManager->execute('addPageSectionIds', $args);
    }

    public function getSettingsId($objectOrId)
    {

        // ------------------------------------------------------
        // Comment Leo: Impossible in PHP => Commented out
        // ------------------------------------------------------
        // // target: pageSection or Block, or already pssId or bsId (when called from a .tmpl.js file)
        // if ($.type(objectOrId) == 'object') {

        //     var object = objectOrId;
        //     return object.attr('data-moduleoutputname');
        // }
        // ------------------------------------------------------
        // End Comment Leo: Impossible in PHP => Commented out
        // ------------------------------------------------------

        return $objectOrId;
    }

    public function getUniqueId($domain)
    {
        $tlFeedback = $this->getTopLevelFeedback($domain);
        return $tlFeedback[\PoP\ComponentModel\Constants\Response::UNIQUE_ID];
    }

    public function getPageSectionConfiguration($domain, $pageSection)
    {
        $pssId = $this->getSettingsId($pageSection);
        $requestHelperService = RequestHelperServiceFacade::getInstance();
        $url = $requestHelperService->getCurrentURL();
        return $this->getCombinedStateData($domain, $url)['settings']['configuration'][$pssId];
    }

    public function getTargetConfiguration($domain, $pageSection, $target, $moduleName)
    {
        $modulePath = $this->getModulePath($domain, $pageSection, $target, $moduleName);
        $targetConfiguration = $this->getPageSectionConfiguration($domain, $pageSection);

        // Go down all levels of the configuration, until finding the level for the module-cb
        if ($modulePath) {
            foreach ($modulePath as $pathLevel) {
                $targetConfiguration = $targetConfiguration[$pathLevel];
            }
        }

        // We reached the target configuration. Now override with the new values
        return $targetConfiguration;
    }

    public function getTemplate($domain, $moduleOrTemplateName)
    {

        // If empty, then the module is already the template
        $templates = $this->getTemplates($domain);
        return $templates[$moduleOrTemplateName] ?? $moduleOrTemplateName;
    }

    public function initPageSectionSettings($domain, $pageSection, &$psConfiguration)
    {

        // Initialize TopLevel / Blocks from the info provided in the feedback
        $tls = $this->getTopLevelSettings($domain);
        $psConfiguration['tls'] = $tls;

        $pss = $this->getPageSectionSettings($domain, $pageSection);
        $pssId = $this->getSettingsId($pageSection);
        $psId = $psConfiguration[GD_JS_FRONTENDID];
        $pss['psId'] = $psId; // This line was added to the PHP, it's not there in the JS
        $psConfiguration['pss'] = $pss;
        // // In addition, the pageSection must merge with the runtimeConfiguration, which is otherwise done in function enterModule
        // if ($psRuntimeConfiguration = $this->getRuntimeConfiguration($domain, $pssId, $pssId, $psConfiguration[GD_JS_MODULE])) {
        //     foreach ($psRuntimeConfiguration as $key => $value) {
        //         $psConfiguration[$key] = $value;
        //     }
        // }

        // Expand the JS Keys for the configuration
        $this->expandJSKeys($psConfiguration);

        // Fill each block configuration with its pssId/bsId/settings
        if ($psConfiguration[ComponentModelComponentInfo::get('response-prop-submodules')]) {
            foreach ($psConfiguration[ComponentModelComponentInfo::get('response-prop-submodules')] as $bsId => &$bConfiguration) {
                $bId = $bConfiguration[GD_JS_FRONTENDID];
                $bs = $this->getBlockSettings($domain, $domain, $pssId, $bsId, $psId, $bId);
                $bConfiguration/*$psConfiguration[ComponentModelComponentInfo::get('response-prop-submodules')][$bsId]*/['tls'] = $tls;
                $bConfiguration/*$psConfiguration[ComponentModelComponentInfo::get('response-prop-submodules')][$bsId]*/['pss'] = $pss;
                $bConfiguration/*$psConfiguration[ComponentModelComponentInfo::get('response-prop-submodules')][$bsId]*/['bs'] = $bs;

                // Expand the JS Keys for the configuration
                $this->expandJSKeys($bConfiguration/*$psConfiguration[ComponentModelComponentInfo::get('response-prop-submodules')][$bsId]*/);
            }
        }
    }

    public function getTopLevelSettings($domain)
    {

        // Comment Leo 24/08/2017: no need for the pre-defined ID
        //$multidomain_websites = PoP_MultiDomain_Utils::getMultidomainWebsites();
        return array(
            'domain' => $domain,
            'domain-id' => /*$multidomain_websites[$domain] ? $multidomain_websites[$domain]['id'] : */RequestUtils::getDomainId($domain),
            'feedback' => $this->getTopLevelFeedback($domain),
        );
    }

    public function getPageSectionSettings($domain, $pageSection)
    {
        $pssId = $this->getSettingsId($pageSection);
        // $psId = $pageSection.attr('id');

        $pageSectionSettings = array(
            'feedback' => $this->getPageSectionFeedback($domain, $pageSection),
            'pssId' => $pssId,
            // 'psId' => $psId,
        );

        return $pageSectionSettings;
    }

    public function isMultiDomain($blockTLDomain, $pssId, $bsId)
    {

        // Comments Leo 27/07/2017: the query-multidomain-urls are stored under the domain from which the block was initially rendered,
        // and not that from where the data is being rendered
        // $multidomain_urls = $this->getRuntimeSettings($blockTLDomain, $pssId, $bsId, 'query-multidomain-urls');
        $requestHelperService = RequestHelperServiceFacade::getInstance();
        $url = $requestHelperService->getCurrentURL();
        $runtimeConfiguration = $this->getStatefulSettings($blockTLDomain, $url, $pssId, $bsId, 'configuration');
        $multidomain_urls = $runtimeConfiguration['query-multidomain-urls'];
        return ($multidomain_urls && count($multidomain_urls) >= 2);
    }

    public function getBlockSettings($domain, $blockTLDomain, $pssId, $bsId, $psId, $bId)
    {
        $blockSettings = array(
            'dbkeys' => $this->getDatabaseKeys($domain, $pssId, $bsId),
            'dbobjectids' => $this->getDataset($domain, $pssId, $bsId),
            'feedback' => $this->getBlockFeedback($domain, $pssId, $bsId),
            'bsId' => $bsId,
            'bId' => $bId,
            'toplevel-domain' => $blockTLDomain,
            'is-multidomain' => $this->isMultiDomain($blockTLDomain, $pssId, $bsId),
        );

        return $blockSettings;
    }

    public function getDataset($domain, $pageSection, $block)
    {
        $pssId = $this->getSettingsId($pageSection);
        $bsId = $this->getSettingsId($block);
        $requestHelperService = RequestHelperServiceFacade::getInstance();
        $url = $requestHelperService->getCurrentURL();
        return $this->getStatefulData($domain, $url)['dbobjectids'][$pssId][$bsId];
    }

    public function getBlockFeedback($domain, $pageSection, $block)
    {
        $pssId = $this->getSettingsId($pageSection);
        $bsId = $this->getSettingsId($block);
        $requestHelperService = RequestHelperServiceFacade::getInstance();
        $url = $requestHelperService->getCurrentURL();
        return $this->getStatefulData($domain, $url)['feedback']['block'][$pssId][$bsId];
    }

    public function getPageSectionFeedback($domain, $pageSection)
    {
        $pssId = $this->getSettingsId($pageSection);
        $requestHelperService = RequestHelperServiceFacade::getInstance();
        $url = $requestHelperService->getCurrentURL();
        return $this->getStatefulData($domain, $url)['feedback']['pagesection'][$pssId];
    }

    public function getTopLevelFeedback($domain)
    {
        $requestHelperService = RequestHelperServiceFacade::getInstance();
        $url = $requestHelperService->getCurrentURL();
        return $this->getStatefulData($domain, $url)['feedback']['toplevel'];
    }

    public function getStatelessSettings($domain, $pageSection, $target, $item)
    {
        $pssId = $this->getSettingsId($pageSection);
        $targetId = $this->getSettingsId($target);

        return $this->getStatelessData($domain)['settings'][$item][$pssId][$targetId];
    }

    public function getStatefulSettings($domain, $url, $pageSection, $target, $item)
    {
        $pssId = $this->getSettingsId($pageSection);
        $targetId = $this->getSettingsId($target);

        return $this->getStatefulData($domain, $url)['settings'][$item][$pssId][$targetId];
    }

    public function getDatabaseKeys($domain, $pageSection, $block)
    {
        return $this->getStatelessSettings($domain, $pageSection, $block, 'dbkeys');
    }

    public function getDestroyUrl($url)
    {
        $domain = GeneralUtils::getDomain($url);
        return $domain.'/destroy'.substr($url, strlen($domain));
    }

    public function getModuleOrObjectSettingsId($el)
    {

        // ------------------------------------------------------
        // Comment Leo: Impossible in PHP => Commented out
        // ------------------------------------------------------
        // // If it's an object, return an attribute
        // if ($.type(el) == 'object') {

        //     return el.data('modulename');
        // }
        // ------------------------------------------------------
        // End Comment Leo: Impossible in PHP => Commented out
        // ------------------------------------------------------

        // String was passed, return it
        return $el;
    }

    public function getModulePath($domain, $pageSection, $target, $moduleName)
    {
        $modulePaths = $this->getStatelessSettings($domain, $pageSection, $target, 'modules-paths');
        return $modulePaths[$moduleName];
    }

    public function getExecutableTemplate($domain, $moduleOrTemplateName)
    {
        $template = $this->getTemplate($domain, $moduleOrTemplateName);
        return PoP_ServerSideRenderingFactory::getInstance()->getTemplateRenderer($template);
    }

    public function getHtml($domain, $moduleOrTemplateName, $context)
    {
        $executableTemplate = $this->getExecutableTemplate($domain, $moduleOrTemplateName);
        // Comment Leo 29/11/2014: some browser plug-ins will not allow the template to be created
        // Eg: AdBlock Plus. So when that happens (eg: when requesting template "socialmedia-source") template is undefined
        // So if this happens, then just return nothing
        if (!$executableTemplate) {
            $error = 'No template for '.$moduleOrTemplateName;
        } else {
            try {
                return $executableTemplate($context);
            } catch (Exception $e) {
                // Do nothing
                $error = 'Error in '.$moduleName.': '+$e->getMessage();
            }
        }

        // If it reached here, it's because there is some error. This should be enabled only on DEV
        if (PoP_HTMLCSSPlatform_ServerUtils::throwExceptionOnTemplateError()) {
            throw new Exception($error);
        }

        error_log($error);
        return '';
    }

    public function getDBObject($domain, $dbKey, $ojectID)
    {
        $userItem = $item = array();
        $userstatedatabase = $this->getUserStateDatabase($domain);
        $primarydatabase = $this->getPrimaryDatabase($domain);
        if ($userstatedatabase[$dbKey] && $userstatedatabase[$dbKey][$ojectID]) {
            $userItem = $userstatedatabase[$dbKey][$ojectID];
        }
        if ($primarydatabase[$dbKey] && $primarydatabase[$dbKey][$ojectID]) {
            $item = $primarydatabase[$dbKey][$ojectID];
        }
        return array_merge(
            $userItem,
            $item
        );
    }
}

/**
 * Initialization
 */
new PoP_ServerSideManager();
