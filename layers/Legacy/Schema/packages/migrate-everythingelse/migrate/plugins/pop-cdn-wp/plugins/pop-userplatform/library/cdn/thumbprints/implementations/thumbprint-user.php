<?php

class PoP_CDNWP_WP_Thumbprint_User extends PoP_CDN_Thumbprint_User
{
    public function getQuery()
    {
        // Add the "meta_key" value needed by WordPress
        return array_merge(
            parent::getQuery(),
            [
                'meta_key' => \PoPCMSSchema\UserMeta\Utils::getMetaKey(GD_METAKEY_PROFILE_LASTEDITED),
            ]
        );
    }
}
    
/**
 * Initialize
 */
new PoP_CDNWP_WP_Thumbprint_User();
