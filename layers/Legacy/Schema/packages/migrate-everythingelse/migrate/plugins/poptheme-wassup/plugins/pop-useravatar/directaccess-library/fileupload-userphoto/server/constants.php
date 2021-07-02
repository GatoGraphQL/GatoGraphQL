<?php

// These are duplicated from the website. Using this ugly hack, because the fileupload server
// is not connected with the WP application, so we need to duplicate what avatar sizes must be generated
define('GD_AVATAR_SIZE_16', 16);
define('GD_AVATAR_SIZE_24', 24);
define('GD_AVATAR_SIZE_26', 26);
define('GD_AVATAR_SIZE_32', 32);
define('GD_AVATAR_SIZE_40', 40);
define('GD_AVATAR_SIZE_50', 50);
define('GD_AVATAR_SIZE_60', 60);
define('GD_AVATAR_SIZE_64', 64);
define('GD_AVATAR_SIZE_82', 82);
define('GD_AVATAR_SIZE_100', 100);
define('GD_AVATAR_SIZE_120', 120);
define('GD_AVATAR_SIZE_150', 150);

if (!defined('POP_USERAVATAR_FILEUPLOAD_SIZES')) {
    define(
        'POP_USERAVATAR_FILEUPLOAD_SIZES',
        array(
            GD_AVATAR_SIZE_16,
            GD_AVATAR_SIZE_24,
            GD_AVATAR_SIZE_26,
            GD_AVATAR_SIZE_32,
            GD_AVATAR_SIZE_40,
            GD_AVATAR_SIZE_50,
            GD_AVATAR_SIZE_60,
            GD_AVATAR_SIZE_64,
            GD_AVATAR_SIZE_82,
            GD_AVATAR_SIZE_100,
            GD_AVATAR_SIZE_120,
            GD_AVATAR_SIZE_150,
        )
    );
}

if (!defined('POP_USERAVATAR_RELATIVEBASEDIR')) {
    define('POP_USERAVATAR_RELATIVEBASEDIR', DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR);
}
