<?php
/**
Helper functions, they have the same logic as the original javascript helper file wp-content/plugins/pop-coreprocessors/js/helpers.handlebars.js
*/
class EM_PoPProcessors_ServerSide_HelperCallers {
    
    public static function locationsPageURL($domain, $options) { 

        global $em_popprocessors_serverside_helpers;
        return $em_popprocessors_serverside_helpers->locationsPageURL($domain, $options);
    }
}