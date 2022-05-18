<?php

use PoP\ComponentModel\ModuleInfo as ComponentModelModuleInfo;

class PoP_ServerSide_MultiLayoutHelpers
{
    public function withConditionalOnDataFieldModule($dbKey, $objectID, $conditionDataFieldModules, $defaultModule, $context, $options)
    {
        $tls = $context['tls'];
        $domain = $tls['domain'];

        $popManager = PoP_ServerSide_LibrariesFactory::getPopmanagerInstance();
        $dbObject = $popManager->getDBObject($domain, $dbKey, $objectID);

        // Fetch the layout for that particular configuration
        $layout = '';
        foreach ($conditionDataFieldModules as $conditionField => $moduleOutputName) {
            // Check if the property evals to `true`. If so, use the corresponding module
            if ($dbObject[$conditionField]) {
                $layout = $moduleOutputName;
                break;
            }
        }
        if (!$layout) {
            $layout = $defaultModule;
        }

        // If still no layout, then do nothing
        if (!$layout) {
            return '';
        }

        // Render the content from this layout
        $layoutContext = $context[ComponentModelModuleInfo::get('response-prop-subcomponents')][$layout];

        // Add dbKey and objectID back into the context
        $layoutContext = array_merge(
            $layoutContext,
            array(
                'dbKey' => $dbKey,
                'objectID' => $objectID,
            )
        );

        // Expand the JS Keys
        $popManager->expandJSKeys($layoutContext);

        return $options['fn']($layoutContext);
    }

    public function layoutLabel($dbKey, $dbObject, $options)
    {
        $multilayout_labels = PoP_HTMLCSSPlatform_ConfigurationUtils::getMultilayoutLabels();

        $label = '';
        foreach ($dbObject['multilayoutKeys'] as $key) {
            $label = $multilayout_labels[$dbObject[$key]];
            if ($label) {
                break;
            }
        }

        return $label ?? '';
    }
}

/**
 * Initialization
 */
global $pop_serverside_multilayouthelpers;
$pop_serverside_multilayouthelpers = new PoP_ServerSide_MultiLayoutHelpers();
