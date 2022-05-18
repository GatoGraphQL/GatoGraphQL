<?php
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\State\ApplicationState;

define('POP_HOOK_PROCESSORBASE_PAGESECTIONJSMETHOD', 'processorbase-pagesectionjsmethod');
define('POP_HOOK_PROCESSORBASE_BLOCKJSMETHOD', 'processorbase-blockjsmethod');

abstract class PoP_WebPlatformQueryDataComponentProcessorBase extends PoP_HTMLCSSPlatformQueryDataComponentProcessorBase
{

    //-------------------------------------------------
    // New PUBLIC Functions
    //-------------------------------------------------

    public function getJsmethodsModuletree(array $componentVariation, array &$props): array
    {
        return $this->executeOnSelfAndPropagateToModules('getProcessedJsmethods', __FUNCTION__, $componentVariation, $props);
    }

    public function getPagesectionJsmethods(array $componentVariation, array &$props): array
    {
        return $this->executeOnSelfAndPropagateToModules('getModulePagesectionJsmethods', __FUNCTION__, $componentVariation, $props);
    }

    public function getImmutableSettings(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableSettings($componentVariation, $props);

        // Validate that the platform level includes this one
        if (!in_array(POP_STRATUM_WEB, \PoP\Root\App::getState('strata'))) {
            return $ret;
        }

        if ($jsmethods = $this->getProcessedJsmethods($componentVariation, $props)) {
            $ret['jsmethods'] = $jsmethods;
        }
        if ($pagesection_jsmethods = $this->getModulePagesectionJsmethods($componentVariation, $props)) {
            $ret['pagesection-jsmethods'] = $pagesection_jsmethods;
        }

        // Allow PoP Resource Loader to inject this value
        return \PoP\Root\App::applyFilters(
            'PoP_WebPlatformQueryDataComponentProcessorBase:module-immutable-settings',
            $ret,
            $componentVariation,
            $props,
            $this
        );
    }

    public function getProcessedJsmethods(array $componentVariation, array &$props): array
    {
        $jsmethods = $this->getJsmethods($componentVariation, $props);

        // Allow the theme to modify the jsmethods
        return \PoP\Root\App::applyFilters(POP_HOOK_PROCESSORBASE_BLOCKJSMETHOD, $jsmethods, $componentVariation);

        // // $ret data structure:
        // // module
        // // methods: map of group => methods
        // // next: repeats this sequence down the line for all the module's modules
        // if ($priority_jsmethod = $this->get_module_filtered_jsmethods($componentVariation, $props)) {

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

    public function getModulePagesectionJsmethods(array $componentVariation, array &$props): array
    {
        $methods = array();

        // $ret data structure:
        // module
        // methods: map of group => methods
        // next: repeats this sequence down the line for all the module's modules
        if ($priority_jsmethod = $this->getModuleFilteredPagesectionJsmethods($componentVariation, $props)) {
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

    public function getImmutableJssettingsModuletree(array $componentVariation, array &$props): array
    {
        return $this->executeOnSelfAndPropagateToModules('getImmutableJssettings', __FUNCTION__, $componentVariation, $props);
    }

    public function getImmutableJssettings(array $componentVariation, array &$props): array
    {
        $ret = array();

        if ($configuration = $this->getImmutableJsconfiguration($componentVariation, $props)) {
            $ret['configuration'] = $configuration;
        }

        if ($initialization_fn = $this->getInitializationjsmethod($componentVariation, $props)) {
            $ret['initializationfn'] = $initialization_fn;
        }

        return $ret;
    }

    public function getImmutableJsconfiguration(array $componentVariation, array &$props): array
    {
        if ($jsconfiguration = $this->getProp($componentVariation, $props, 'immutable-jsconfiguration')) {
            return $jsconfiguration;
        }

        return array();
    }

    public function getInitializationjsmethod(array $componentVariation, array &$props)
    {
        return null;
    }

    //-------------------------------------------------
    // New PUBLIC Functions: Stateful JS Settings
    //-------------------------------------------------

    public function getMutableonmodelJssettingsModuletree(array $componentVariation, array &$props): array
    {
        return $this->executeOnSelfAndPropagateToModules('getMutableonmodelJssettings', __FUNCTION__, $componentVariation, $props);
    }

    public function getMutableonmodelJssettings(array $componentVariation, array &$props): array
    {
        $ret = array();

        if ($configuration = $this->getMutableonmodelJsconfiguration($componentVariation, $props)) {
            $ret['configuration'] = $configuration;
        }

        return $ret;
    }

    public function getMutableonmodelJsconfiguration(array $componentVariation, array &$props): array
    {
        if ($jsconfiguration = $this->getProp($componentVariation, $props, 'mutableonmodel-jsconfiguration')) {
            return $jsconfiguration;
        }

        return array();
    }

    //-------------------------------------------------
    // New PUBLIC Functions: Stateful Settings
    //-------------------------------------------------

    public function getMutableonrequestJssettingsModuletree(array $componentVariation, array &$props): array
    {
        return $this->executeOnSelfAndPropagateToModules('getMutableonrequestJssettings', __FUNCTION__, $componentVariation, $props);
    }

    public function getMutableonrequestJssettings(array $componentVariation, array &$props): array
    {
        $ret = array();

        if ($configuration = $this->getMutableonrequestJsconfiguration($componentVariation, $props)) {
            $ret['configuration'] = $configuration;
        }

        return $ret;
    }

    public function getMutableonrequestJsconfiguration(array $componentVariation, array &$props): array
    {
        if ($jsconfiguration = $this->getProp($componentVariation, $props, 'mutableonrequest-jsconfiguration')) {
            return $jsconfiguration;
        }

        return array();
    }

    //-------------------------------------------------
    // New PUBLIC Functions: Data Feedback
    //-------------------------------------------------

    public function getJsdataFeedbackDatasetmoduletree(array $componentVariation, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $dbobjectids): array
    {
        return $this->executeOnSelfAndPropagateToDatasetmodules('getJsdataFeedbackModuletree', __FUNCTION__, $componentVariation, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids);
    }

    public function getJsdataFeedbackModuletree(array $componentVariation, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $dbobjectids): array
    {
        $ret = array();

        if ($feedback = $this->getJsdataFeedback($componentVariation, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids)) {
            $ret[\PoP\ComponentModel\Constants\DataLoading::FEEDBACK] = $feedback;
        }

        return $ret;
    }

    public function getJsdataFeedback(array $componentVariation, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $dbobjectids): array
    {
        return array();
    }

    public function getMutableonrequestConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getMutableonrequestConfiguration($componentVariation, $props);

        // Validate that the platform level includes this one
        if (!in_array(POP_STRATUM_WEB, \PoP\Root\App::getState('strata'))) {
            return $ret;
        }

        $moduleOutputName = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($componentVariation);

        // The Intercept URLs are runtime instead of static, since they contains information
        // given through the URL, which cannot not cached in the static file
        if ($intercept_urls = $this->getModuleInterceptUrls($componentVariation, $props)) {
            $ret[GD_JS_INTERCEPTURLS][$moduleOutputName] = $intercept_urls;
        }
        if ($extra_intercept_urls = $this->getModuleExtraInterceptUrls($componentVariation, $props)) {
            $ret[GD_JS_EXTRAINTERCEPTURLS][$moduleOutputName] = $extra_intercept_urls;
        }

        // Allow CSS to Styles to modify these value
        return \PoP\Root\App::applyFilters(
            'PoP_WebPlatformQueryDataComponentProcessorBase:module-mutableonrequest-configuration',
            $ret,
            $componentVariation,
            $props,
            $this
        );
    }

    public function addWebPlatformModuleConfiguration(&$ret, array $componentVariation, array &$props)
    {

        // Do nothing. Override
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        // Validate that the platform level includes this one
        if (!in_array(POP_STRATUM_WEB, \PoP\Root\App::getState('strata'))) {
            return $ret;
        }

        // Add the webplatform stuff
        $this->addWebPlatformModuleConfiguration($ret, $componentVariation, $props);

        /**
         * Interceptor
         */
        if ($intercept_urls = $this->getModuleInterceptUrls($componentVariation, $props)) {
            $intercept_type = $this->getInterceptType($componentVariation, $props);
            $ret[GD_JS_INTERCEPT] = array(
                GD_JS_TYPE => $intercept_type ? $intercept_type : 'fullurl'
            );
            if ($intercept_settings = $this->getInterceptSettings($componentVariation, $props)) {
                $ret[GD_JS_INTERCEPT][GD_JS_SETTINGS] = implode(GD_SEPARATOR, $intercept_settings);
            }
            if ($intercept_target = $this->getInterceptTarget($componentVariation, $props)) {
                $ret[GD_JS_INTERCEPT][GD_JS_TARGET] = $intercept_target;
            }
            if ($this->getInterceptSkipstateupdate($componentVariation, $props)) {
                $ret[GD_JS_INTERCEPT][GD_JS_SKIPSTATEUPDATE] = true;
            }
        }

        /**
         * Make an object "lazy": allow to append html to it
         */
        if ($appendable = $this->getProp($componentVariation, $props, 'appendable')) {
            $ret[GD_JS_APPENDABLE] = true;
            $ret[GD_JS_CLASSES][GD_JS_APPENDABLE] = $this->getProp($componentVariation, $props, 'appendable-class');
        }

        // Allow PoP Resource Loader to inject this value
        return \PoP\Root\App::applyFilters(
            'PoP_WebPlatformQueryDataComponentProcessorBase:module-immutable-configuration',
            $ret,
            $componentVariation,
            $props,
            $this
        );
    }

    //-------------------------------------------------
    // Intercept URLs
    //-------------------------------------------------

    public function getIntercepturlsMergedmoduletree(array $componentVariation, array &$props)
    {
        return $this->executeOnSelfAndMergeWithModules('getInterceptUrls', __FUNCTION__, $componentVariation, $props, false);
    }

    public function getInterceptUrls(array $componentVariation, array &$props)
    {
        if ($module_intercept_urls = $this->getModuleInterceptUrls($componentVariation, $props)) {
            return array_unique(array_values($module_intercept_urls));
        }

        return array();
    }
    public function getModuleInterceptUrls(array $componentVariation, array &$props)
    {
        return array();
    }
    public function getModuleExtraInterceptUrls(array $componentVariation, array &$props)
    {
        return array();
    }
    public function getInterceptSettings(array $componentVariation, array &$props)
    {
        return array();
    }
    public function getInterceptType(array $componentVariation, array &$props)
    {
        return 'fullurl';
    }
    public function getInterceptTarget(array $componentVariation, array &$props)
    {
        return null;
    }
    public function getInterceptSkipstateupdate(array $componentVariation, array &$props)
    {
        return false;
    }

    // protected function setModuleWebPlatformProps(array $componentVariation, array &$props) {

    // 	if ($this->getProp($componentVariation, $props, 'lazy-load')) {

    // 		$this->appendProp($componentVariation, $props, 'class', POP_CLASS_LOADINGCONTENT);
    // 	}
    // }

    public function initWebPlatformModelProps(array $componentVariation, array &$props)
    {
        // // Add the properties below either as static or mutableonrequest
        // if (in_array($this->getDatasource($componentVariation, $props), array(
        // 	\PoP\ComponentModel\Constants\DataSources::IMMUTABLE,
        // 	\PoP\ComponentModel\Constants\DataSources::MUTABLEONMODEL,
        // ))) {

        // 	$this->setModuleWebPlatformProps($componentVariation, $props);
        // }
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        // Validate that the platform level includes this one
        if (in_array(POP_STRATUM_WEB, \PoP\Root\App::getState('strata'))) {

            $this->initWebPlatformModelProps($componentVariation, $props);
        }

        parent::initModelProps($componentVariation, $props);
    }

    public function initWebPlatformRequestProps(array $componentVariation, array &$props)
    {

        // // Add the properties below either as static or mutableonrequest
        // if ($this->getDatasource($componentVariation, $props) == \PoP\ComponentModel\Constants\DataSources::MUTABLEONREQUEST) {

        // 	$this->setModuleWebPlatformProps($componentVariation, $props);
        // }
    }

    public function initRequestProps(array $componentVariation, array &$props): void
    {
        // Validate that the platform level includes this one
        if (in_array(POP_STRATUM_WEB, \PoP\Root\App::getState('strata'))) {

            $this->initWebPlatformRequestProps($componentVariation, $props);
        }

        parent::initRequestProps($componentVariation, $props);
    }

    //-------------------------------------------------
    // PROTECTED Functions
    //-------------------------------------------------

    protected function getPagesectionJsmethod(array $componentVariation, array &$props)
    {
        $ret = array();
        $priorities = array(
            POP_PROGRESSIVEBOOTING_CRITICAL,
            POP_PROGRESSIVEBOOTING_NONCRITICAL,
        );
        foreach ($priorities as $priority) {
            if ($jsmethods = $this->getProp($componentVariation, $props, 'pagesection-jsmethod-'.$priority)) {
                $ret[$priority] = $jsmethods;
            }
        }

        return $ret;
    }
    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = array();
        $priorities = array(
            POP_PROGRESSIVEBOOTING_CRITICAL,
            POP_PROGRESSIVEBOOTING_NONCRITICAL,
        );
        foreach ($priorities as $priority) {
            if ($group_jsmethods = $this->getProp($componentVariation, $props, 'jsmethods-'.$priority)) {
                foreach ($group_jsmethods as $group => $jsmethods) {
                    foreach ($jsmethods as $jsmethod) {
                        $this->addJsmethod($ret, $jsmethod, $group, false, $priority);
                    }
                }
            }
        }

        return $ret;
    }
    protected function getModuleFilteredPagesectionJsmethods(array $componentVariation, array &$props)
    {
        $jsmethod = $this->getPagesectionJsmethod($componentVariation, $props);
        $jsmethod = \PoP\Root\App::applyFilters(POP_HOOK_PROCESSORBASE_PAGESECTIONJSMETHOD, $jsmethod, $componentVariation);

        return $jsmethod;
    }

    public function addJsmethod(&$ret, $method, $group = GD_JSMETHOD_GROUP_MAIN, $unshift = false, $priority = null)
    {
        PoPWebPlatform_ModuleManager_Utils::addJsmethod($ret, $method, $group, $unshift, $priority);
    }
    public function mergePagesectionJsmethodProp(array $componentVariation, array &$props, $methods, $group = GD_JSMETHOD_GROUP_MAIN, $priority = null)
    {
        $priority = $priority ?? POP_PROGRESSIVEBOOTING_NONCRITICAL;
        $this->mergeTargetJsmethodProp($componentVariation, $props, 'pagesection-jsmethod-'.$priority, $methods, $group);
    }
    public function mergeJsmethodsProp(array $componentVariation, array &$props, $methods, $group = GD_JSMETHOD_GROUP_MAIN, $priority = null)
    {
        $priority = $priority ?? POP_PROGRESSIVEBOOTING_NONCRITICAL;
        $this->mergeTargetJsmethodProp($componentVariation, $props, 'jsmethods-'.$priority, $methods, $group);
    }
    public function mergeImmutableJsconfigurationProp(array $componentVariation, array &$props, $jsconfiguration)
    {
        $this->mergeIterateKeyProp($componentVariation, $props, 'immutable-jsconfiguration', $jsconfiguration);
    }
    public function mergeMutableonmodelJsconfigurationProp(array $componentVariation, array &$props, $jsconfiguration)
    {
        $this->mergeIterateKeyProp($componentVariation, $props, 'mutableonmodel-jsconfiguration', $jsconfiguration);
    }
    public function mergeMutableonrequestJsconfigurationProp(array $componentVariation, array &$props, $jsconfiguration)
    {
        $this->mergeIterateKeyProp($componentVariation, $props, 'mutableonrequest-jsconfiguration', $jsconfiguration);
    }

    //-------------------------------------------------
    // PRIVATE Functions
    //-------------------------------------------------

    private function mergeTargetJsmethodProp(array $componentVariation, array &$props, $target_key, $methods, $group)
    {
        $group = $group ? $group : GD_JSMETHOD_GROUP_MAIN;
        // $this->merge_group_iterate_key_att(POP_PROPS_JSMETHODS, $componentVariation, $props, $target_key, array(
        $this->mergeIterateKeyProp($componentVariation, $props, $target_key, array(
            $group => $methods,
        ));
    }
}
