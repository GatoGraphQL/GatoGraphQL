<?php
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\State\ApplicationState;

define('POP_HOOK_PROCESSORBASE_PAGESECTIONJSMETHOD', 'processorbase-pagesectionjsmethod');
define('POP_HOOK_PROCESSORBASE_BLOCKJSMETHOD', 'processorbase-blockjsmethod');

abstract class PoP_WebPlatformQueryDataModuleProcessorBase extends PoP_HTMLCSSPlatformQueryDataModuleProcessorBase
{

    //-------------------------------------------------
    // New PUBLIC Functions
    //-------------------------------------------------

    public function getJsmethodsModuletree(array $module, array &$props): array
    {
        return $this->executeOnSelfAndPropagateToModules('getProcessedJsmethods', __FUNCTION__, $module, $props);
    }

    public function getPagesectionJsmethods(array $module, array &$props): array
    {
        return $this->executeOnSelfAndPropagateToModules('getModulePagesectionJsmethods', __FUNCTION__, $module, $props);
    }

    public function getImmutableSettings(array $module, array &$props): array
    {
        $ret = parent::getImmutableSettings($module, $props);

        // Validate that the platform level includes this one
        if (!in_array(POP_STRATUM_WEB, \PoP\Root\App::getState('strata'))) {
            return $ret;
        }

        if ($jsmethods = $this->getProcessedJsmethods($module, $props)) {
            $ret['jsmethods'] = $jsmethods;
        }
        if ($pagesection_jsmethods = $this->getModulePagesectionJsmethods($module, $props)) {
            $ret['pagesection-jsmethods'] = $pagesection_jsmethods;
        }

        // Allow PoP Resource Loader to inject this value
        return \PoP\Root\App::applyFilters(
            'PoP_WebPlatformQueryDataModuleProcessorBase:module-immutable-settings',
            $ret,
            $module,
            $props,
            $this
        );
    }

    public function getProcessedJsmethods(array $module, array &$props): array
    {
        $jsmethods = $this->getJsmethods($module, $props);

        // Allow the theme to modify the jsmethods
        return \PoP\Root\App::applyFilters(POP_HOOK_PROCESSORBASE_BLOCKJSMETHOD, $jsmethods, $module);

        // // $ret data structure:
        // // module
        // // methods: map of group => methods
        // // next: repeats this sequence down the line for all the module's modules
        // if ($priority_jsmethod = $this->get_module_filtered_jsmethods($module, $props)) {

        // 	foreach ($priority_jsmethod as $priority => $jsmethod) {

        // 		if (!$jsmethod) {
        // 			continue;
        // 		}
        // 		$methods[$priority] = $methods[$priority] ?? array();
        // 		$methods[$priority] = array_merge(
        // 			$methods[$priority],
        // 			$jsmethod
        // 		);
        // 	}
        // }

        // return $methods;
    }

    public function getModulePagesectionJsmethods(array $module, array &$props): array
    {
        $methods = array();

        // $ret data structure:
        // module
        // methods: map of group => methods
        // next: repeats this sequence down the line for all the module's modules
        if ($priority_jsmethod = $this->getModuleFilteredPagesectionJsmethods($module, $props)) {
            foreach ($priority_jsmethod as $priority => $jsmethod) {
                if (!$jsmethod) {
                    continue;
                }
                $methods[$priority] = $methods[$priority] ?? array();
                $methods[$priority] = array_merge(
                    $methods[$priority],
                    $jsmethod
                );
            }
        }

        return $methods;
    }

    //-------------------------------------------------
    // New PUBLIC Functions: Static JS Settings
    //-------------------------------------------------

    public function getImmutableJssettingsModuletree(array $module, array &$props): array
    {
        return $this->executeOnSelfAndPropagateToModules('getImmutableJssettings', __FUNCTION__, $module, $props);
    }

    public function getImmutableJssettings(array $module, array &$props): array
    {
        $ret = array();

        if ($configuration = $this->getImmutableJsconfiguration($module, $props)) {
            $ret['configuration'] = $configuration;
        }

        if ($initialization_fn = $this->getInitializationjsmethod($module, $props)) {
            $ret['initializationfn'] = $initialization_fn;
        }

        return $ret;
    }

    public function getImmutableJsconfiguration(array $module, array &$props): array
    {
        if ($jsconfiguration = $this->getProp($module, $props, 'immutable-jsconfiguration')) {
            return $jsconfiguration;
        }

        return array();
    }

    public function getInitializationjsmethod(array $module, array &$props)
    {
        return null;
    }

    //-------------------------------------------------
    // New PUBLIC Functions: Stateful JS Settings
    //-------------------------------------------------

    public function getMutableonmodelJssettingsModuletree(array $module, array &$props): array
    {
        return $this->executeOnSelfAndPropagateToModules('getMutableonmodelJssettings', __FUNCTION__, $module, $props);
    }

    public function getMutableonmodelJssettings(array $module, array &$props): array
    {
        $ret = array();

        if ($configuration = $this->getMutableonmodelJsconfiguration($module, $props)) {
            $ret['configuration'] = $configuration;
        }

        return $ret;
    }

    public function getMutableonmodelJsconfiguration(array $module, array &$props): array
    {
        if ($jsconfiguration = $this->getProp($module, $props, 'mutableonmodel-jsconfiguration')) {
            return $jsconfiguration;
        }

        return array();
    }

    //-------------------------------------------------
    // New PUBLIC Functions: Stateful Settings
    //-------------------------------------------------

    public function getMutableonrequestJssettingsModuletree(array $module, array &$props): array
    {
        return $this->executeOnSelfAndPropagateToModules('getMutableonrequestJssettings', __FUNCTION__, $module, $props);
    }

    public function getMutableonrequestJssettings(array $module, array &$props): array
    {
        $ret = array();

        if ($configuration = $this->getMutableonrequestJsconfiguration($module, $props)) {
            $ret['configuration'] = $configuration;
        }

        return $ret;
    }

    public function getMutableonrequestJsconfiguration(array $module, array &$props): array
    {
        if ($jsconfiguration = $this->getProp($module, $props, 'mutableonrequest-jsconfiguration')) {
            return $jsconfiguration;
        }

        return array();
    }

    //-------------------------------------------------
    // New PUBLIC Functions: Data Feedback
    //-------------------------------------------------

    public function getJsdataFeedbackDatasetmoduletree(array $module, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $dbobjectids): array
    {
        return $this->executeOnSelfAndPropagateToDatasetmodules('getJsdataFeedbackModuletree', __FUNCTION__, $module, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids);
    }

    public function getJsdataFeedbackModuletree(array $module, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $dbobjectids): array
    {
        $ret = array();

        if ($feedback = $this->getJsdataFeedback($module, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids)) {
            $ret[\PoP\ComponentModel\Constants\DataLoading::FEEDBACK] = $feedback;
        }

        return $ret;
    }

    public function getJsdataFeedback(array $module, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $dbobjectids): array
    {
        return array();
    }

    public function getMutableonrequestConfiguration(array $module, array &$props): array
    {
        $ret = parent::getMutableonrequestConfiguration($module, $props);

        // Validate that the platform level includes this one
        if (!in_array(POP_STRATUM_WEB, \PoP\Root\App::getState('strata'))) {
            return $ret;
        }

        $moduleOutputName = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($module);

        // The Intercept URLs are runtime instead of static, since they contains information
        // given through the URL, which cannot not cached in the static file
        if ($intercept_urls = $this->getModuleInterceptUrls($module, $props)) {
            $ret[GD_JS_INTERCEPTURLS][$moduleOutputName] = $intercept_urls;
        }
        if ($extra_intercept_urls = $this->getModuleExtraInterceptUrls($module, $props)) {
            $ret[GD_JS_EXTRAINTERCEPTURLS][$moduleOutputName] = $extra_intercept_urls;
        }

        // Allow CSS to Styles to modify these value
        return \PoP\Root\App::applyFilters(
            'PoP_WebPlatformQueryDataModuleProcessorBase:module-mutableonrequest-configuration',
            $ret,
            $module,
            $props,
            $this
        );
    }

    public function addWebPlatformModuleConfiguration(&$ret, array $module, array &$props)
    {

        // Do nothing. Override
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        // Validate that the platform level includes this one
        if (!in_array(POP_STRATUM_WEB, \PoP\Root\App::getState('strata'))) {
            return $ret;
        }

        // Add the webplatform stuff
        $this->addWebPlatformModuleConfiguration($ret, $module, $props);

        /**
         * Interceptor
         */
        if ($intercept_urls = $this->getModuleInterceptUrls($module, $props)) {
            $intercept_type = $this->getInterceptType($module, $props);
            $ret[GD_JS_INTERCEPT] = array(
                GD_JS_TYPE => $intercept_type ? $intercept_type : 'fullurl'
            );
            if ($intercept_settings = $this->getInterceptSettings($module, $props)) {
                $ret[GD_JS_INTERCEPT][GD_JS_SETTINGS] = implode(GD_SEPARATOR, $intercept_settings);
            }
            if ($intercept_target = $this->getInterceptTarget($module, $props)) {
                $ret[GD_JS_INTERCEPT][GD_JS_TARGET] = $intercept_target;
            }
            if ($this->getInterceptSkipstateupdate($module, $props)) {
                $ret[GD_JS_INTERCEPT][GD_JS_SKIPSTATEUPDATE] = true;
            }
        }

        /**
         * Make an object "lazy": allow to append html to it
         */
        if ($appendable = $this->getProp($module, $props, 'appendable')) {
            $ret[GD_JS_APPENDABLE] = true;
            $ret[GD_JS_CLASSES][GD_JS_APPENDABLE] = $this->getProp($module, $props, 'appendable-class');
        }

        // Allow PoP Resource Loader to inject this value
        return \PoP\Root\App::applyFilters(
            'PoP_WebPlatformQueryDataModuleProcessorBase:module-immutable-configuration',
            $ret,
            $module,
            $props,
            $this
        );
    }

    //-------------------------------------------------
    // Intercept URLs
    //-------------------------------------------------

    public function getIntercepturlsMergedmoduletree(array $module, array &$props)
    {
        return $this->executeOnSelfAndMergeWithModules('getInterceptUrls', __FUNCTION__, $module, $props, false);
    }

    public function getInterceptUrls(array $module, array &$props)
    {
        if ($module_intercept_urls = $this->getModuleInterceptUrls($module, $props)) {
            return array_unique(array_values($module_intercept_urls));
        }

        return array();
    }
    public function getModuleInterceptUrls(array $module, array &$props)
    {
        return array();
    }
    public function getModuleExtraInterceptUrls(array $module, array &$props)
    {
        return array();
    }
    public function getInterceptSettings(array $module, array &$props)
    {
        return array();
    }
    public function getInterceptType(array $module, array &$props)
    {
        return 'fullurl';
    }
    public function getInterceptTarget(array $module, array &$props)
    {
        return null;
    }
    public function getInterceptSkipstateupdate(array $module, array &$props)
    {
        return false;
    }

    // protected function setModuleWebPlatformProps(array $module, array &$props) {

    // 	if ($this->getProp($module, $props, 'lazy-load')) {

    // 		$this->appendProp($module, $props, 'class', POP_CLASS_LOADINGCONTENT);
    // 	}
    // }

    public function initWebPlatformModelProps(array $module, array &$props)
    {
        // // Add the properties below either as static or mutableonrequest
        // if (in_array($this->getDatasource($module, $props), array(
        // 	\PoP\ComponentModel\Constants\DataSources::IMMUTABLE,
        // 	\PoP\ComponentModel\Constants\DataSources::MUTABLEONMODEL,
        // ))) {

        // 	$this->setModuleWebPlatformProps($module, $props);
        // }
    }

    public function initModelProps(array $module, array &$props): void
    {
        // Validate that the platform level includes this one
        if (in_array(POP_STRATUM_WEB, \PoP\Root\App::getState('strata'))) {

            $this->initWebPlatformModelProps($module, $props);
        }

        parent::initModelProps($module, $props);
    }

    public function initWebPlatformRequestProps(array $module, array &$props)
    {

        // // Add the properties below either as static or mutableonrequest
        // if ($this->getDatasource($module, $props) == \PoP\ComponentModel\Constants\DataSources::MUTABLEONREQUEST) {

        // 	$this->setModuleWebPlatformProps($module, $props);
        // }
    }

    public function initRequestProps(array $module, array &$props): void
    {
        // Validate that the platform level includes this one
        if (in_array(POP_STRATUM_WEB, \PoP\Root\App::getState('strata'))) {

            $this->initWebPlatformRequestProps($module, $props);
        }

        parent::initRequestProps($module, $props);
    }

    //-------------------------------------------------
    // PROTECTED Functions
    //-------------------------------------------------

    protected function getPagesectionJsmethod(array $module, array &$props)
    {
        $ret = array();
        $priorities = array(
            POP_PROGRESSIVEBOOTING_CRITICAL,
            POP_PROGRESSIVEBOOTING_NONCRITICAL,
        );
        foreach ($priorities as $priority) {
            if ($jsmethods = $this->getProp($module, $props, 'pagesection-jsmethod-'.$priority)) {
                $ret[$priority] = $jsmethods;
            }
        }

        return $ret;
    }
    public function getJsmethods(array $module, array &$props)
    {
        $ret = array();
        $priorities = array(
            POP_PROGRESSIVEBOOTING_CRITICAL,
            POP_PROGRESSIVEBOOTING_NONCRITICAL,
        );
        foreach ($priorities as $priority) {
            if ($group_jsmethods = $this->getProp($module, $props, 'jsmethods-'.$priority)) {
                foreach ($group_jsmethods as $group => $jsmethods) {
                    foreach ($jsmethods as $jsmethod) {
                        $this->addJsmethod($ret, $jsmethod, $group, false, $priority);
                    }
                }
            }
        }

        return $ret;
    }
    protected function getModuleFilteredPagesectionJsmethods(array $module, array &$props)
    {
        $jsmethod = $this->getPagesectionJsmethod($module, $props);
        $jsmethod = \PoP\Root\App::applyFilters(POP_HOOK_PROCESSORBASE_PAGESECTIONJSMETHOD, $jsmethod, $module);

        return $jsmethod;
    }

    public function addJsmethod(&$ret, $method, $group = GD_JSMETHOD_GROUP_MAIN, $unshift = false, $priority = null)
    {
        PoPWebPlatform_ModuleManager_Utils::addJsmethod($ret, $method, $group, $unshift, $priority);
    }
    public function mergePagesectionJsmethodProp(array $module, array &$props, $methods, $group = GD_JSMETHOD_GROUP_MAIN, $priority = null)
    {
        $priority = $priority ?? POP_PROGRESSIVEBOOTING_NONCRITICAL;
        $this->mergeTargetJsmethodProp($module, $props, 'pagesection-jsmethod-'.$priority, $methods, $group);
    }
    public function mergeJsmethodsProp(array $module, array &$props, $methods, $group = GD_JSMETHOD_GROUP_MAIN, $priority = null)
    {
        $priority = $priority ?? POP_PROGRESSIVEBOOTING_NONCRITICAL;
        $this->mergeTargetJsmethodProp($module, $props, 'jsmethods-'.$priority, $methods, $group);
    }
    public function mergeImmutableJsconfigurationProp(array $module, array &$props, $jsconfiguration)
    {
        $this->mergeIterateKeyProp($module, $props, 'immutable-jsconfiguration', $jsconfiguration);
    }
    public function mergeMutableonmodelJsconfigurationProp(array $module, array &$props, $jsconfiguration)
    {
        $this->mergeIterateKeyProp($module, $props, 'mutableonmodel-jsconfiguration', $jsconfiguration);
    }
    public function mergeMutableonrequestJsconfigurationProp(array $module, array &$props, $jsconfiguration)
    {
        $this->mergeIterateKeyProp($module, $props, 'mutableonrequest-jsconfiguration', $jsconfiguration);
    }

    //-------------------------------------------------
    // PRIVATE Functions
    //-------------------------------------------------

    private function mergeTargetJsmethodProp(array $module, array &$props, $target_key, $methods, $group)
    {
        $group = $group ? $group : GD_JSMETHOD_GROUP_MAIN;
        // $this->merge_group_iterate_key_att(POP_PROPS_JSMETHODS, $module, $props, $target_key, array(
        $this->mergeIterateKeyProp($module, $props, $target_key, array(
            $group => $methods,
        ));
    }
}
