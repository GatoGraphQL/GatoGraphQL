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

    public function getJsmethodsModuletree(array $component, array &$props): array
    {
        return $this->executeOnSelfAndPropagateToComponents('getProcessedJsmethods', __FUNCTION__, $component, $props);
    }

    public function getPagesectionJsmethods(array $component, array &$props): array
    {
        return $this->executeOnSelfAndPropagateToComponents('getModulePagesectionJsmethods', __FUNCTION__, $component, $props);
    }

    public function getImmutableSettings(array $component, array &$props): array
    {
        $ret = parent::getImmutableSettings($component, $props);

        // Validate that the platform level includes this one
        if (!in_array(POP_STRATUM_WEB, \PoP\Root\App::getState('strata'))) {
            return $ret;
        }

        if ($jsmethods = $this->getProcessedJsmethods($component, $props)) {
            $ret['jsmethods'] = $jsmethods;
        }
        if ($pagesection_jsmethods = $this->getModulePagesectionJsmethods($component, $props)) {
            $ret['pagesection-jsmethods'] = $pagesection_jsmethods;
        }

        // Allow PoP Resource Loader to inject this value
        return \PoP\Root\App::applyFilters(
            'PoP_WebPlatformQueryDataComponentProcessorBase:component-immutable-settings',
            $ret,
            $component,
            $props,
            $this
        );
    }

    public function getProcessedJsmethods(array $component, array &$props): array
    {
        $jsmethods = $this->getJsmethods($component, $props);

        // Allow the theme to modify the jsmethods
        return \PoP\Root\App::applyFilters(POP_HOOK_PROCESSORBASE_BLOCKJSMETHOD, $jsmethods, $component);

        // // $ret data structure:
        // // component
        // // methods: map of group => methods
        // // next: repeats this sequence down the line for all the component's components
        // if ($priority_jsmethod = $this->get_component_filtered_jsmethods($component, $props)) {

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

    public function getModulePagesectionJsmethods(array $component, array &$props): array
    {
        $methods = array();

        // $ret data structure:
        // component
        // methods: map of group => methods
        // next: repeats this sequence down the line for all the component's components
        if ($priority_jsmethod = $this->getComponentFilteredPagesectionJsmethods($component, $props)) {
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

    public function getImmutableJssettingsModuletree(array $component, array &$props): array
    {
        return $this->executeOnSelfAndPropagateToComponents('getImmutableJssettings', __FUNCTION__, $component, $props);
    }

    public function getImmutableJssettings(array $component, array &$props): array
    {
        $ret = array();

        if ($configuration = $this->getImmutableJsconfiguration($component, $props)) {
            $ret['configuration'] = $configuration;
        }

        if ($initialization_fn = $this->getInitializationjsmethod($component, $props)) {
            $ret['initializationfn'] = $initialization_fn;
        }

        return $ret;
    }

    public function getImmutableJsconfiguration(array $component, array &$props): array
    {
        if ($jsconfiguration = $this->getProp($component, $props, 'immutable-jsconfiguration')) {
            return $jsconfiguration;
        }

        return array();
    }

    public function getInitializationjsmethod(array $component, array &$props)
    {
        return null;
    }

    //-------------------------------------------------
    // New PUBLIC Functions: Stateful JS Settings
    //-------------------------------------------------

    public function getMutableonmodelJssettingsModuletree(array $component, array &$props): array
    {
        return $this->executeOnSelfAndPropagateToComponents('getMutableonmodelJssettings', __FUNCTION__, $component, $props);
    }

    public function getMutableonmodelJssettings(array $component, array &$props): array
    {
        $ret = array();

        if ($configuration = $this->getMutableonmodelJsconfiguration($component, $props)) {
            $ret['configuration'] = $configuration;
        }

        return $ret;
    }

    public function getMutableonmodelJsconfiguration(array $component, array &$props): array
    {
        if ($jsconfiguration = $this->getProp($component, $props, 'mutableonmodel-jsconfiguration')) {
            return $jsconfiguration;
        }

        return array();
    }

    //-------------------------------------------------
    // New PUBLIC Functions: Stateful Settings
    //-------------------------------------------------

    public function getMutableonrequestJssettingsModuletree(array $component, array &$props): array
    {
        return $this->executeOnSelfAndPropagateToComponents('getMutableonrequestJssettings', __FUNCTION__, $component, $props);
    }

    public function getMutableonrequestJssettings(array $component, array &$props): array
    {
        $ret = array();

        if ($configuration = $this->getMutableonrequestJsconfiguration($component, $props)) {
            $ret['configuration'] = $configuration;
        }

        return $ret;
    }

    public function getMutableonrequestJsconfiguration(array $component, array &$props): array
    {
        if ($jsconfiguration = $this->getProp($component, $props, 'mutableonrequest-jsconfiguration')) {
            return $jsconfiguration;
        }

        return array();
    }

    //-------------------------------------------------
    // New PUBLIC Functions: Data Feedback
    //-------------------------------------------------

    public function getJsdataFeedbackDatasetcomponenttree(array $component, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $dbobjectids): array
    {
        return $this->executeOnSelfAndPropagateToDatasetcomponents('getJsdataFeedbackModuletree', __FUNCTION__, $component, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids);
    }

    public function getJsdataFeedbackModuletree(array $component, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $dbobjectids): array
    {
        $ret = array();

        if ($feedback = $this->getJsdataFeedback($component, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids)) {
            $ret[\PoP\ComponentModel\Constants\DataLoading::FEEDBACK] = $feedback;
        }

        return $ret;
    }

    public function getJsdataFeedback(array $component, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $dbobjectids): array
    {
        return array();
    }

    public function getMutableonrequestConfiguration(array $component, array &$props): array
    {
        $ret = parent::getMutableonrequestConfiguration($component, $props);

        // Validate that the platform level includes this one
        if (!in_array(POP_STRATUM_WEB, \PoP\Root\App::getState('strata'))) {
            return $ret;
        }

        $componentOutputName = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($component);

        // The Intercept URLs are runtime instead of static, since they contains information
        // given through the URL, which cannot not cached in the static file
        if ($intercept_urls = $this->getModuleInterceptUrls($component, $props)) {
            $ret[GD_JS_INTERCEPTURLS][$componentOutputName] = $intercept_urls;
        }
        if ($extra_intercept_urls = $this->getModuleExtraInterceptUrls($component, $props)) {
            $ret[GD_JS_EXTRAINTERCEPTURLS][$componentOutputName] = $extra_intercept_urls;
        }

        // Allow CSS to Styles to modify these value
        return \PoP\Root\App::applyFilters(
            'PoP_WebPlatformQueryDataComponentProcessorBase:component-mutableonrequest-configuration',
            $ret,
            $component,
            $props,
            $this
        );
    }

    public function addWebPlatformModuleConfiguration(&$ret, array $component, array &$props)
    {

        // Do nothing. Override
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        // Validate that the platform level includes this one
        if (!in_array(POP_STRATUM_WEB, \PoP\Root\App::getState('strata'))) {
            return $ret;
        }

        // Add the webplatform stuff
        $this->addWebPlatformModuleConfiguration($ret, $component, $props);

        /**
         * Interceptor
         */
        if ($intercept_urls = $this->getModuleInterceptUrls($component, $props)) {
            $intercept_type = $this->getInterceptType($component, $props);
            $ret[GD_JS_INTERCEPT] = array(
                GD_JS_TYPE => $intercept_type ? $intercept_type : 'fullurl'
            );
            if ($intercept_settings = $this->getInterceptSettings($component, $props)) {
                $ret[GD_JS_INTERCEPT][GD_JS_SETTINGS] = implode(GD_SEPARATOR, $intercept_settings);
            }
            if ($intercept_target = $this->getInterceptTarget($component, $props)) {
                $ret[GD_JS_INTERCEPT][GD_JS_TARGET] = $intercept_target;
            }
            if ($this->getInterceptSkipstateupdate($component, $props)) {
                $ret[GD_JS_INTERCEPT][GD_JS_SKIPSTATEUPDATE] = true;
            }
        }

        /**
         * Make an object "lazy": allow to append html to it
         */
        if ($appendable = $this->getProp($component, $props, 'appendable')) {
            $ret[GD_JS_APPENDABLE] = true;
            $ret[GD_JS_CLASSES][GD_JS_APPENDABLE] = $this->getProp($component, $props, 'appendable-class');
        }

        // Allow PoP Resource Loader to inject this value
        return \PoP\Root\App::applyFilters(
            'PoP_WebPlatformQueryDataComponentProcessorBase:component-immutable-configuration',
            $ret,
            $component,
            $props,
            $this
        );
    }

    //-------------------------------------------------
    // Intercept URLs
    //-------------------------------------------------

    public function getIntercepturlsMergedcomponenttree(array $component, array &$props)
    {
        return $this->executeOnSelfAndMergeWithComponents('getInterceptUrls', __FUNCTION__, $component, $props, false);
    }

    public function getInterceptUrls(array $component, array &$props)
    {
        if ($component_intercept_urls = $this->getModuleInterceptUrls($component, $props)) {
            return array_unique(array_values($component_intercept_urls));
        }

        return array();
    }
    public function getModuleInterceptUrls(array $component, array &$props)
    {
        return array();
    }
    public function getModuleExtraInterceptUrls(array $component, array &$props)
    {
        return array();
    }
    public function getInterceptSettings(array $component, array &$props)
    {
        return array();
    }
    public function getInterceptType(array $component, array &$props)
    {
        return 'fullurl';
    }
    public function getInterceptTarget(array $component, array &$props)
    {
        return null;
    }
    public function getInterceptSkipstateupdate(array $component, array &$props)
    {
        return false;
    }

    // protected function setModuleWebPlatformProps(array $component, array &$props) {

    // 	if ($this->getProp($component, $props, 'lazy-load')) {

    // 		$this->appendProp($component, $props, 'class', POP_CLASS_LOADINGCONTENT);
    // 	}
    // }

    public function initWebPlatformModelProps(array $component, array &$props)
    {
        // // Add the properties below either as static or mutableonrequest
        // if (in_array($this->getDatasource($component, $props), array(
        // 	\PoP\ComponentModel\Constants\DataSources::IMMUTABLE,
        // 	\PoP\ComponentModel\Constants\DataSources::MUTABLEONMODEL,
        // ))) {

        // 	$this->setModuleWebPlatformProps($component, $props);
        // }
    }

    public function initModelProps(array $component, array &$props): void
    {
        // Validate that the platform level includes this one
        if (in_array(POP_STRATUM_WEB, \PoP\Root\App::getState('strata'))) {

            $this->initWebPlatformModelProps($component, $props);
        }

        parent::initModelProps($component, $props);
    }

    public function initWebPlatformRequestProps(array $component, array &$props)
    {

        // // Add the properties below either as static or mutableonrequest
        // if ($this->getDatasource($component, $props) == \PoP\ComponentModel\Constants\DataSources::MUTABLEONREQUEST) {

        // 	$this->setModuleWebPlatformProps($component, $props);
        // }
    }

    public function initRequestProps(array $component, array &$props): void
    {
        // Validate that the platform level includes this one
        if (in_array(POP_STRATUM_WEB, \PoP\Root\App::getState('strata'))) {

            $this->initWebPlatformRequestProps($component, $props);
        }

        parent::initRequestProps($component, $props);
    }

    //-------------------------------------------------
    // PROTECTED Functions
    //-------------------------------------------------

    protected function getPagesectionJsmethod(array $component, array &$props)
    {
        $ret = array();
        $priorities = array(
            POP_PROGRESSIVEBOOTING_CRITICAL,
            POP_PROGRESSIVEBOOTING_NONCRITICAL,
        );
        foreach ($priorities as $priority) {
            if ($jsmethods = $this->getProp($component, $props, 'pagesection-jsmethod-'.$priority)) {
                $ret[$priority] = $jsmethods;
            }
        }

        return $ret;
    }
    public function getJsmethods(array $component, array &$props)
    {
        $ret = array();
        $priorities = array(
            POP_PROGRESSIVEBOOTING_CRITICAL,
            POP_PROGRESSIVEBOOTING_NONCRITICAL,
        );
        foreach ($priorities as $priority) {
            if ($group_jsmethods = $this->getProp($component, $props, 'jsmethods-'.$priority)) {
                foreach ($group_jsmethods as $group => $jsmethods) {
                    foreach ($jsmethods as $jsmethod) {
                        $this->addJsmethod($ret, $jsmethod, $group, false, $priority);
                    }
                }
            }
        }

        return $ret;
    }
    protected function getComponentFilteredPagesectionJsmethods(array $component, array &$props)
    {
        $jsmethod = $this->getPagesectionJsmethod($component, $props);
        $jsmethod = \PoP\Root\App::applyFilters(POP_HOOK_PROCESSORBASE_PAGESECTIONJSMETHOD, $jsmethod, $component);

        return $jsmethod;
    }

    public function addJsmethod(&$ret, $method, $group = GD_JSMETHOD_GROUP_MAIN, $unshift = false, $priority = null)
    {
        PoPWebPlatform_ModuleManager_Utils::addJsmethod($ret, $method, $group, $unshift, $priority);
    }
    public function mergePagesectionJsmethodProp(array $component, array &$props, $methods, $group = GD_JSMETHOD_GROUP_MAIN, $priority = null)
    {
        $priority = $priority ?? POP_PROGRESSIVEBOOTING_NONCRITICAL;
        $this->mergeTargetJsmethodProp($component, $props, 'pagesection-jsmethod-'.$priority, $methods, $group);
    }
    public function mergeJsmethodsProp(array $component, array &$props, $methods, $group = GD_JSMETHOD_GROUP_MAIN, $priority = null)
    {
        $priority = $priority ?? POP_PROGRESSIVEBOOTING_NONCRITICAL;
        $this->mergeTargetJsmethodProp($component, $props, 'jsmethods-'.$priority, $methods, $group);
    }
    public function mergeImmutableJsconfigurationProp(array $component, array &$props, $jsconfiguration)
    {
        $this->mergeIterateKeyProp($component, $props, 'immutable-jsconfiguration', $jsconfiguration);
    }
    public function mergeMutableonmodelJsconfigurationProp(array $component, array &$props, $jsconfiguration)
    {
        $this->mergeIterateKeyProp($component, $props, 'mutableonmodel-jsconfiguration', $jsconfiguration);
    }
    public function mergeMutableonrequestJsconfigurationProp(array $component, array &$props, $jsconfiguration)
    {
        $this->mergeIterateKeyProp($component, $props, 'mutableonrequest-jsconfiguration', $jsconfiguration);
    }

    //-------------------------------------------------
    // PRIVATE Functions
    //-------------------------------------------------

    private function mergeTargetJsmethodProp(array $component, array &$props, $target_key, $methods, $group)
    {
        $group = $group ? $group : GD_JSMETHOD_GROUP_MAIN;
        // $this->merge_group_iterate_key_att(POP_PROPS_JSMETHODS, $component, $props, $target_key, array(
        $this->mergeIterateKeyProp($component, $props, $target_key, array(
            $group => $methods,
        ));
    }
}
