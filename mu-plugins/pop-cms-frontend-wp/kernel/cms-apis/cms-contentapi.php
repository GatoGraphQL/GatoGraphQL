<?php
namespace PoP\CMS\WP;

class FrontendContentAPI extends ContentAPI implements \PoP\CMS\FrontendContentAPI
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
new FrontendContentAPI();
