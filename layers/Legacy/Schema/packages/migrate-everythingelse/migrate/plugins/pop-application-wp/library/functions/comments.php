<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

// // Add all WP default filters also into the comments

// // Taken from wp-includes/default-filters.php
// HooksAPIFacade::getInstance()->addFilter('gd_comments_content', 'capital_P_dangit', 11);
// HooksAPIFacade::getInstance()->addFilter('gd_comments_content', 'wptexturize');
// HooksAPIFacade::getInstance()->addFilter('gd_comments_content', 'convert_smilies');
// HooksAPIFacade::getInstance()->addFilter('gd_comments_content', 'convert_chars');
// HooksAPIFacade::getInstance()->addFilter('gd_comments_content', 'wpautop');
// HooksAPIFacade::getInstance()->addFilter('gd_comments_content', 'shortcode_unautop');
// HooksAPIFacade::getInstance()->addFilter('gd_comments_content', 'prepend_attachment');

// // Taken from wp-includes/shortcodes.php
// HooksAPIFacade::getInstance()->addFilter('gd_comments_content', 'do_shortcode', 11); // AFTER wpautop()

// Add the autoembed too
HooksAPIFacade::getInstance()->addFilter('comment_text', array($GLOBALS['wp_embed'], 'autoembed'), 8);
