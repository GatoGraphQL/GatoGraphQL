<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

// Remove the canonical, since we are already printing this info in the header (and we do it ourselves, as to remove the language information)
// Originally added in wp-includes/default-filters.php
HooksAPIFacade::getInstance()->removeAction('wp_head', 'rel_canonical');

// We don't need the shortlink or the generator
HooksAPIFacade::getInstance()->removeAction('wp_head', 'wp_shortlink_wp_head', 10, 0);
HooksAPIFacade::getInstance()->removeAction('wp_head', 'wp_generator');

// This outputs site_url( 'xmlrpc.php' ), and because the xmlrpc.php is blocked, no need to add it
HooksAPIFacade::getInstance()->removeAction('wp_head', 'rsd_link'); // Display the link to the Really Simple Discovery service endpoint, EditURI link

// This fails when we're not in the loop:
// <b>Warning</b>:  count(): Parameter must be an array or an object that implements Countable in <b>/Users/leo/Sites/PoP/wp-includes/post-template.php</b> on line <b>284</b><br />
// Because of this line in wp-includes/formatting.php:3308, which doesn't pass the $postID:
// $text = get_the_content('');
HooksAPIFacade::getInstance()->removeFilter( 'get_the_excerpt', 'wp_trim_excerpt'  );
