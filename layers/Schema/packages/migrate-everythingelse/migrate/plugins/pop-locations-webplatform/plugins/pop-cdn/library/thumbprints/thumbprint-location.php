<?php

define('POP_EM_CDN_THUMBPRINT_LOCATION', 'location');

class PoP_EM_CDN_Thumbprint_Location extends PoP_CDN_Thumbprint_PostBase
{
    public function getName()
    {
        return POP_EM_CDN_THUMBPRINT_LOCATION;
    }

    public function getQuery()
    {
        $pluginapi = PoP_Locations_APIFactory::getInstance();
        return array_merge(
            parent::getQuery(),
            array(
                'custompost-types' => array($pluginapi->getLocationPostType()),
            )
        );
    }
}

/**
 * Initialize
 */
new PoP_EM_CDN_Thumbprint_Location();
