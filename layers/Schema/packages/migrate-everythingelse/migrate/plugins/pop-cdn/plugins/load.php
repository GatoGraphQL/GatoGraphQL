<?php

if (defined('POP_USERSTATE_INITIALIZED')) {
    include_once 'pop-userstate/load.php';
}

if (defined('POP_SYSTEM_INITIALIZED')) {
    include_once 'pop-system/load.php';
}
if (defined('POP_BLOG_INITIALIZED')) {
    include_once 'pop-blog/load.php';
}
if (defined('POP_TRENDINGTAGS_INITIALIZED')) {
    include_once 'pop-trendingtags/load.php';
}
if (defined('POP_USERCOMMUNITIES_INITIALIZED')) {
    include_once 'pop-usercommunities/load.php';
}
if (defined('POP_COMMONUSERROLES_INITIALIZED')) {
    include_once 'pop-commonuserroles/load.php';
}

if (defined('POP_USERPLATFORM_INITIALIZED')) {
    include_once 'pop-userplatform/load.php';
}

if (defined('POP_RESOURCELOADER_INITIALIZED')) {
    include_once 'pop-resourceloader/load.php';
}
