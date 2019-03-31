<?php
namespace PoP\CMSModel\WP;

class ContentAPI extends \PoP\CMS\WP\ContentAPI implements \PoP\CMSModel\ContentAPI
{

    public function autoembed($content)
    {
        $wp_embed = $GLOBALS['wp_embed'];
        return $wp_embed->autoembed($content);   
    }
}

/**
 * Initialize
 */
new ContentAPI();
