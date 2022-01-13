<?php

use PoP\ComponentModel\ComponentInfo as ComponentModelComponentInfo;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\Root\Facades\Hooks\HooksAPIFacade;

/**
 * Helper functions, they have the same logic as the original javascript
 * helper file wp-content/plugins/pop-engine-webplatform/js/helpers.handlebars.js
 */
class PoP_ServerSide_KernelHelpers
{
    public function destroyUrl($url, $options)
    {
        $popManager = PoP_ServerSide_LibrariesFactory::getPopmanagerInstance();
        return new LS($popManager->getDestroyUrl($url));
    }

    public function iffirstload($options)
    {
        $context = $options['hash']['context'] ?? $options['_this'];
        $pssId = $options['hash']['pssId'] ?? $context['pss']['pssId'];
        $popManager = PoP_ServerSide_LibrariesFactory::getPopmanagerInstance();
        $condition = $popManager->isFirstLoad($pssId);

        if ($condition) {
            return $options['fn']();
        } else {
            return $options['inverse']();
        }
    }

    public function interceptAttr($options)
    {
        $context = $options['hash']['context'] ?? $options['_this'];
        $intercept = $context[GD_JS_INTERCEPT] ?? array();

        return new LS(($intercept[GD_JS_TYPE] ? ' data-intercept="'.$intercept[GD_JS_TYPE].'"' : '') . ($intercept['settings'] ? ' data-intercept-settings="'.$intercept[GD_JS_SETTINGS].'"' : '') . ($intercept[GD_JS_TARGET] ? ' target="'.$intercept[GD_JS_TARGET].'"' : '') . ($intercept[GD_JS_SKIPSTATEUPDATE] ? ' data-intercept-skipstateupdate="true"' : '') . ' style="display: none;"');
    }

    public function generateId($options)
    {
        $context = $options['hash']['context'] ?? $options['_this'];
        $pssId = $options['hash']['pssId'] ?? $context['pss']['pssId'];
        $targetId = $options['hash']['targetId'] ?? $context['bs']['bsId'];
        $moduleName = $options['hash']['module'] ?? $context[GD_JS_MODULE];
        $fixed = $options['hash']['fixed'] ?? $context[GD_JS_FIXEDID];
        $isIdUnique = $options['hash']['idUnique'] ?? $context[GD_JS_ISIDUNIQUE];
        $group = $options['hash']['group'];
        $id = $options['fn']();
        $ignorePSRuntimeId = $context['ignorePSRuntimeId'];
        $domain = $context['tls']['domain'];

        // Print also the block URL. Needed to know under what URL to save the session-ids.
        // Set the URL before calling addModule, where it will be needed
        $popJSRuntimeManager = PoP_ServerSide_LibrariesFactory::getJsruntimeInstance();
        $url = $options['hash']['addURL'] ? $context['tls']['feedback'][\PoP\ComponentModel\Constants\Response::URL] : '';
        if ($url) {
            $popJSRuntimeManager->setBlockURL($domain, $url);
        }

        $generatedId = $popJSRuntimeManager->addModule($domain, $pssId, $targetId, $moduleName, $id, $group, $fixed, $isIdUnique, $ignorePSRuntimeId);
        $items = array();
        $items[] = 'id="'.$generatedId.'"';
        $items[] = 'data-modulename="'.$moduleName.'"';

        // For the block, also add the URL on which it was first generated (not initialized... it can be initialized later on)
        if ($url) {
            $items[] = 'data-'.POP_PARAMS_TOPLEVEL_URL.'="'.$url.'"';
            $items[] = 'data-'.POP_PARAMS_TOPLEVEL_DOMAIN.'="'.GeneralUtils::getDomain($url).'"';
        }
        return new LS(implode(' ', $items));
    }

    public function lastGeneratedId($options)
    {
        $context = $options['hash']['context'] ?? $options['_this'];
        $pssId = $options['hash']['pssId'] ?? $context['pss']['pssId'];
        $targetId = $options['hash']['targetId'] ?? $context['bs']['bsId'];
        $moduleName = $options['hash']['module'] ?? $context[GD_JS_MODULE];

        $domain = $context['tls']['domain'];
        $group = $options['hash']['group'];
        $popJSRuntimeManager = PoP_ServerSide_LibrariesFactory::getJsruntimeInstance();
        return $popJSRuntimeManager->getLastGeneratedId($domain, $pssId, $targetId, $moduleName, $group);
    }

    public function enterTemplate($template, $options)
    {
        $context = $options['hash']['context'] ?? $options['_this'];
        $domain = $context['tls']['domain'];
        $popManager = PoP_ServerSide_LibrariesFactory::getPopmanagerInstance();
        $response = $popManager->getHtml($domain, $template, $context);
        return new LS($response/*, 'encq'*/);
    }

    /* Comment Leo: taken from http://jsfiddle.net/dain/NRjUb/ */
    public function enterModule($prevContext, $options)
    {

        // The context can be passed as a param, or if null, use the current one
        $context = $options['hash']['context'] ?? $options['_this'];
        $moduleName = $options['hash']['module'] ?? $context[GD_JS_MODULE];

        // From the prevContext we rescue the topLevel/pageSection/block Settings
        $tls = $prevContext['tls'];
        $pss = $prevContext['pss'];
        $bs = $prevContext['bs'];
        $dbObject = $prevContext['dbObject'];
        $dbObjectDBKey = $prevContext['dbObjectDBKey'];
        $ignorePSRuntimeId = $prevContext['ignorePSRuntimeId'];
        $feedbackObject = $prevContext['feedbackObject'];

        // The following values, if passed as a param, then these take priority. Otherwise, use them from the previous context
        $dbKey = $options['hash']['dbKey'] ?? $prevContext['dbKey'];
        $objectIDs = $options['hash']['objectIDs'] ?? $prevContext['objectIDs'];

        // Add all these vars to the context for this module
        $extend = array(
            'dbObject' => $dbObject,
            'dbObjectDBKey' => $dbObjectDBKey,
            'dbKey' => $dbKey,
            'objectIDs' => $objectIDs,
            'tls' => $tls,
            'pss' => $pss,
            'bs' => $bs,
            'ignorePSRuntimeId' => $ignorePSRuntimeId,
            'feedbackObject' => $feedbackObject,
        );

        $domain = $tls['domain'];
        $pssId = $pss['pssId'];
        $psId = $pss['psId'];
        $bsId = $bs['bsId'];
        $bId = $bs['bId'];

        $popManager = PoP_ServerSide_LibrariesFactory::getPopmanagerInstance();
        // $context = array_merge(
        //     $context,
        //     $popManager->getRuntimeConfiguration($domain, $pssId, $bsId, $moduleName)
        // );

        // Expand the JS Keys
        // Needed in addition to withModule because it's not always used. Eg: controlbuttongroup.tmpl it iterates directly on modules and do enterModule on each, no #with involved
        // Do it after extending with getRuntimeConfiguration, so that these keys are also expanded
        $popManager->expandJSKeys($context);

        // DBObjectId could be passed as an array ('dbobjectids' is an array), so if it's the case, and it's empty, then nullify it
        $objectID = $options['hash']['objectID'];
        if (is_array($objectID)) {
            if (count($objectID)) {
                $objectID = $objectID[0];
            } else {
                $objectID = null;
                $dbObject = null;
                $extend['dbObject'] = $dbObject;
            }
        }

        if (isset($options['hash']['dbKey']) && $objectID) {
            $dbKey = $options['hash']['dbKey'];
            $dbObject = $popManager->getDBObject($domain, $dbKey, $objectID);
            $extend['dbObject'] = $dbObject;
            $extend['dbObjectDBKey'] = $dbKey;
            $extend['dbKey'] = $dbKey;
            $extend['objectIDs'] = array($objectID);
        } elseif (isset($options['hash']['dbKey']) && $options['hash']['objectIDs']) {
            $extend['dbKey'] = $options['hash']['dbKey'];
            $extend['objectIDs'] = $options['hash']['objectIDs'];
        } elseif (isset($options['hash']['subcomponent']) && $objectID) {
            $dbKey = $bs['dbkeys'][$options['hash']['subcomponent']];
            $dbObject = $popManager->getDBObject($domain, $dbKey, $objectID);
            $extend['dbObject'] = $dbObject;
            $extend['dbObjectDBKey'] = $dbKey;
            $extend['dbKey'] = $dbKey;
            $extend['objectIDs'] = array($objectID);
        } elseif (isset($options['hash']['subcomponent']) && $options['hash']['objectIDs']) {
            $dbKey = $bs['dbkeys'][$options['hash']['subcomponent']];
            $extend['dbKey'] = $dbKey;
            $extend['objectIDs'] = $options['hash']['objectIDs'];
        } elseif (isset($options['hash']['objectIDs'])) {
            $extend['objectIDs'] = $options['hash']['objectIDs'];
        } elseif (isset($options['hash']['dbKey'])) {
            // If only the dbKey has value, it means the other value passes (objectID or objectIDs) is null
            // So then put everything to null
            $extend['dbKey'] = $options['hash']['dbKey'];
            $extend['dbObject'] = null;
            $extend['dbObjectDBKey'] = null;
            $extend['objectIDs'] = null;
        }

        // Make sure the objectIDs are an array
        if ($extend['objectIDs'] ?? null) {
            if (!is_array($extend['objectIDs'])) {
                $extend['objectIDs'] = array($extend['objectIDs']);
            }
        }

        if ($options['hash']['feedbackObject'] ?? null) {
            // Allow to get data from an object from the feedback (eg: feedbackmessage)
            $extend['feedbackObject'] = $options['hash']['feedbackObject'];
        }

        $context = array_merge(
            $context,
            $extend
        );

        $response = $popManager->getHtml($domain, $moduleName, $context);

        // Allow PoP Resource Loader to modify the response, to add embedded scripts
        $response = HooksAPIFacade::getInstance()->applyFilters(
            'handlebars-helpers:enterModule:response',
            $response,
            $context,
            $moduleName,
            $domain,
            $pssId,
            $psId,
            $bsId,
            $bId
        );

        if ($options['hash']['unsafe'] ?? null) {
            return $response;
        }

        return new LS($response);
    }

    public function withModule($context, $moduleName, $options)
    {
        // Comment Leo 10/06/2017: here we ask for !isset() and not just !, so that if there is an empty array, it still works...
        if (!$context || !isset($context[GD_JS_SUBMODULEOUTPUTNAMES]) || !isset($context[GD_JS_SUBMODULEOUTPUTNAMES][$moduleName])) {
            return;
        }

        // Get the module settings id from the configuration
        $moduleOutputName = $context[GD_JS_SUBMODULEOUTPUTNAMES][$moduleName];

        // Comment Leo 10/06/2017: here we ask for !isset() and not just !, so that if there is an empty array, it still works...
        if (!isset($context[ComponentModelComponentInfo::get('response-prop-submodules')]) || !isset($context[ComponentModelComponentInfo::get('response-prop-submodules')][$moduleOutputName])) {
            return;
        }

        // Go down to the module
        $context = $context[ComponentModelComponentInfo::get('response-prop-submodules')][$moduleOutputName];

        // Expand the JS Keys
        $popManager = PoP_ServerSide_LibrariesFactory::getPopmanagerInstance();
        $popManager->expandJSKeys($context);

        // Read all hash options, and add them to the Context
        $context = array_merge(
            $context,
            $options['hash'] ?? array()
        );

        return $options['fn']($context);
    }

    public function withSublevel($sublevel, $options)
    {
        $context = $options['hash']['context'] ?? $options['_this'];

        // Expand the JS Keys
        $popManager = PoP_ServerSide_LibrariesFactory::getPopmanagerInstance();
        $popManager->expandJSKeys($context);

        return $options['fn']($context[$sublevel]);
    }

    public function get($db, $index, $options)
    {

        // Comment Leo 10/06/2017: here we ask for !isset() and not just !, so that if there is an empty array, it still works...
        if (!$db || !isset($db[$index])) {
            return '';
        }
        return $db[$index];
    }

    public function ifget($db, $index, $options)
    {
        if (!$db) {
            return '';
        }

        $context = $options['hash']['context'] ?? $options['_this'];
        $condition = false;
        // Allow to execute a method to check for the condition (eg: is user-id from the object equal to website logged in user?)
        if ($options['hash']['method'] ?? null) {
            $popJSLibraryManager = PoP_ServerSide_LibrariesFactory::getJslibraryInstance();
            $args = array(
                'domain' => $context['tls']['domain'],
                'input' => $db[$index],
            );
            $executed = $popJSLibraryManager->execute($options['hash']['method'], $args);
            foreach ($executed as $value) {
                if ($value) {
                    $condition = true;
                    break;
                }
            }
        } else {
            $condition = $db[$index];
        }

        if ($condition) {
            return $options['fn']();
        } else {
            return $options['inverse']();
        }
    }

    public function withget($db, $index, $options)
    {
        // Comment Leo 10/06/2017: here we ask for !isset() and not just !, so that if there is an empty array, it still works...
        if (!$db || !isset($db[$index])) {
            return '';
        }

        $context = $db[$index];

        // Expand the JS Keys
        $popManager = PoP_ServerSide_LibrariesFactory::getPopmanagerInstance();
        $popManager->expandJSKeys($context);

        return $options['fn']($context);
    }
}

/**
 * Initialization
 */
global $pop_serverside_kernelhelpers;
$pop_serverside_kernelhelpers = new PoP_ServerSide_KernelHelpers();
