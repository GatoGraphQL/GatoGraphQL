<?php
// Make sure that the required constans have been provided
if (!AWS_ACCESS_KEY_ID || !AWS_SECRET_ACCESS_KEY) {
    die('Constants AWS_ACCESS_KEY_ID and/or AWS_SECRET_ACCESS_KEY have not been defined');
}
if (!POP_AWS_WORKINGBUCKET) {
    die('Constant POP_AWS_WORKINGBUCKET has not been defined');
}
if (!POP_FILEUPLOAD_AWS_WORKINGBUCKETPREFIX) {
    die('Constant POP_FILEUPLOAD_AWS_WORKINGBUCKETPREFIX has not been defined');
}
