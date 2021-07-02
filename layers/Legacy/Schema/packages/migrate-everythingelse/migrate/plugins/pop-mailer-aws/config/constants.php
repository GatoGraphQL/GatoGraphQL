<?php
// Credentials to access the bucket
if (!defined('POP_MAILER_AWS_ACCESS_KEY_ID')) {
    define('POP_MAILER_AWS_ACCESS_KEY_ID', '');
}
if (!defined('POP_MAILER_AWS_SECRET_ACCESS_KEY')) {
    define('POP_MAILER_AWS_SECRET_ACCESS_KEY', '');
}

// Region where the Emails bucket is registered
if (!defined('POP_MAILER_AWS_REGION')) {
    define('POP_MAILER_AWS_REGION', '');
}

// Email Bucket: where the emails are uploaded
if (!defined('POP_MAILER_AWS_BUCKET')) {
    define('POP_MAILER_AWS_BUCKET', '');
}
// Email Path: path in the bucket to the folder for the website where to upload the emails, so that /emails can hold the emails and /config can hold the configuration in the bucket
if (!defined('POP_MAILER_AWS_PATH')) {
    define('POP_MAILER_AWS_PATH', 'emails/');
}
