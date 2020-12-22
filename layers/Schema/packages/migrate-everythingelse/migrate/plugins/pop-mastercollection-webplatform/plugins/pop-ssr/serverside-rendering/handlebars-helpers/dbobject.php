<?php
/**
Helper functions, they have the same logic as the original javascript helper file wp-content/plugins/pop-engine-webplatform/js/helpers.handlebars.js
 */
class PoP_ServerSide_DBObjectHelpers
{
    public function withDBObject($dbKey, $dbObjectID, $options)
    {
        $context = $options['hash']['context'] ?? $options['_this'];
        $tls = $context['tls'];
        $domain = $tls['domain'];

        // Replace the context with only the dbObject
        $popManager = PoP_ServerSide_LibrariesFactory::getPopmanagerInstance();
        $context = $popManager->getDBObject($domain, $dbKey, $dbObjectID);
        
        return $options['fn']($context);
    }
}

/**
 * Initialization
 */
global $pop_serverside_dbobjecthelpers;
$pop_serverside_dbobjecthelpers = new PoP_ServerSide_DBObjectHelpers();
