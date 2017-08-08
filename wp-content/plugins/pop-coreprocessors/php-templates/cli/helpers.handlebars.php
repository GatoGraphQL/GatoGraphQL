<?php
/**
Helper functions, they have the same logic as the original javascript helper file wp-content/plugins/pop-coreprocessors/js/helpers.handlebars.js
*/
class PoP_Core_ServerSide_HelperCallers {
    
    public static function latestCountTargets($itemObject, $options) { 

        global $pop_core_serverside_helpers;
        return $pop_core_serverside_helpers->latestCountTargets($itemObject, $options);
    }

    public static function formatFeedbackMessage($message, $options) { 

        global $pop_core_serverside_helpers;
        return $pop_core_serverside_helpers->formatFeedbackMessage($message, $options);
    }
}