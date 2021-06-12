<?php

define('POP_CDN_THUMBPRINT_POST', 'post');

class PoP_CDN_Thumbprint_Post extends PoP_CDN_Thumbprint_PostBase
{
    public function getName(): string
    {
        return POP_CDN_THUMBPRINT_POST;
    }

    public function getQuery()
    {
        $cmsapplicationpostsapi = \PoP\Application\PostsFunctionAPIFactory::getInstance();
        return array_merge(
            parent::getQuery(),
            array(
                'custompost-types' => $cmsapplicationpostsapi->getAllcontentPostTypes(),
            )
        );
    }
}

/**
 * Initialize
 */
new PoP_CDN_Thumbprint_Post();
