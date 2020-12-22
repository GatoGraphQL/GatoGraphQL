<?php

// AWS
//--------------------------------------------------------
if (!defined('AWS_ACCESS_KEY_ID')) {
    define('AWS_ACCESS_KEY_ID', '');
}
if (!defined('AWS_SECRET_ACCESS_KEY')) {
    define('AWS_SECRET_ACCESS_KEY', '');
}
if (!defined('POP_AWS_REGION')) {
    define('POP_AWS_REGION', '');
}
if (!defined('POP_AWS_UPLOADSBUCKET')) {
    define('POP_AWS_UPLOADSBUCKET', '');
}

// Working Bucket: where the file upload takes place
if (!defined('POP_AWS_WORKINGBUCKET')) {
    define('POP_AWS_WORKINGBUCKET', '');
}
