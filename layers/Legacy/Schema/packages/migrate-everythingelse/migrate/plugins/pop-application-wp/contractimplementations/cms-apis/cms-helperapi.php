<?php
namespace PoP\Application\WP;

class HelperAPI extends \PoP\Application\HelperAPI_Base
{
    public function escapeHTML(string $text) {

    	return esc_html($text);
    }
    public function escapeAttributes(string $text)
    {
        return esc_attr($text);
    }

    public function makeClickable(string $text) {

    	return make_clickable($text);
    }

    public function convertLinebreaksToHTML(string $text): string {

    	return wpautop($text);
    }

    public function sanitizeTitle(string $title)
    {
        return sanitize_title($title);
    }
}

/**
 * Initialize
 */
new HelperAPI();
