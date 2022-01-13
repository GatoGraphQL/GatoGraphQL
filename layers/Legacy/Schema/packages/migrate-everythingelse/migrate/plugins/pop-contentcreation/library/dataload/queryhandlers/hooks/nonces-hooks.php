<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_Core_DataLoad_NoncesCheckpointQueryInputOutputHandler_Hooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addFilter(
            '\PoP\ComponentModel\Engine:session-meta',
            array($this, 'getSessionMeta')
        );
    }

    public function getSessionMeta($meta)
    {

        // Get the user info? (used for pages where user logged in is needed. Generally same as with checkpoints)
        if (PoP_UserLogin_Utils::getUserInfo()) {
            // Nonces for validation for the Media Manager
            if (!$meta[GD_URLPARAM_NONCES]) {
                $meta[GD_URLPARAM_NONCES] = array();
            }
            $nonces = array(
                'media-form',
                'media-send-to-editor',
            );
            foreach ($nonces as $nonce) {
                $meta[GD_URLPARAM_NONCES][$nonce] = wp_create_nonce($nonce);
            }
        }
        
        return $meta;
    }
}

/**
 * Initialization
 */
new PoP_Core_DataLoad_NoncesCheckpointQueryInputOutputHandler_Hooks();
