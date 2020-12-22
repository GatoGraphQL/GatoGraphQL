<?php

if (class_exists('\PoPSchema\CustomPostsWP\Component')) {
    require_once 'pop-customposts-wp/load.php';
}
if (defined('POP_COMMENTS_INITIALIZED')) {
    require_once 'pop-comments/load.php';
}
if (defined('POP_USERS_INITIALIZED')) {
    require_once 'pop-users/load.php';
}
if (defined('POP_TAXONOMIES_INITIALIZED')) {
    require_once 'pop-taxonomies/load.php';
}
