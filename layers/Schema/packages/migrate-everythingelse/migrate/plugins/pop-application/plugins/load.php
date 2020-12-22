<?php

if (defined('POP_AVATARFOUNDATION_INITIALIZED')) {
    include_once 'pop-avatar-foundation/load.php';
}

if (defined('POP_TAXONOMIES_INITIALIZED')) {
    include_once 'pop-taxonomies/load.php';
}
if (defined('POP_TAXONOMYQUERY_INITIALIZED')) {
    include_once 'pop-taxonomyquery/load.php';
}

if (defined('POP_POSTS_INITIALIZED')) {
	require_once 'pop-posts/load.php';
}
