<?php

// // Add all WP default filters also into the comments

// // Taken from wp-includes/default-filters.php
// \PoP\Root\App::addFilter('gd_comments_content', 'capital_P_dangit', 11);
// \PoP\Root\App::addFilter('gd_comments_content', 'wptexturize');
// \PoP\Root\App::addFilter('gd_comments_content', 'convert_smilies');
// \PoP\Root\App::addFilter('gd_comments_content', 'convert_chars');
// \PoP\Root\App::addFilter('gd_comments_content', 'wpautop');
// \PoP\Root\App::addFilter('gd_comments_content', 'shortcode_unautop');
// \PoP\Root\App::addFilter('gd_comments_content', 'prepend_attachment');

// // Taken from wp-includes/shortcodes.php
// \PoP\Root\App::addFilter('gd_comments_content', 'do_shortcode', 11); // AFTER wpautop()

// Add the autoembed too
\PoP\Root\App::addFilter('comment_text', array($GLOBALS['wp_embed'], 'autoembed'), 8);
