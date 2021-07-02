<?php

use PoP\ComponentModel\ComponentInfo as ComponentModelComponentInfo;

class PoP_ServerSide_MultiLayoutHelpers
{
    public function withConditionalOnDataFieldModule($dbKey, $dbObjectID, $conditionDataFieldModules, $defaultModule, $context, $options)
    {
        $tls = $context['tls'];
        $domain = $tls['domain'];

        $popManager = PoP_ServerSide_LibrariesFactory::getPopmanagerInstance();
        $dbObject = $popManager->getDBObject($domain, $dbKey, $dbObjectID);

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
        $layoutContext = $context[ComponentModelComponentInfo::get('response-prop-submodules')][$layout];

        // Add dbKey and dbObjectID back into the context
        $layoutContext = array_merge(
            $layoutContext,
            array(
                'dbKey' => $dbKey,
                'dbObjectID' => $dbObjectID,
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
