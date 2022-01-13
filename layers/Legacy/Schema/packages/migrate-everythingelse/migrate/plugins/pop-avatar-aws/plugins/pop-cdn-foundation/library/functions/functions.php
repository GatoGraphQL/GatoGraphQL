<?php

// Add the CDN domain for the bucket, for the Uploads bucket, if its CDN URL has been defined
\PoP\Root\App::getHookManager()->addFilter('PoP_Avatar_AWS:bucket_url', 'popAvatarAwsBucketUrl', 10, 2);
function popAvatarAwsBucketUrl($domain, $bucket)
{
    if ($bucket == POP_AWS_UPLOADSBUCKET && defined('POP_CDNFOUNDATION_CDN_UPLOADS_URI') && POP_CDNFOUNDATION_CDN_UPLOADS_URI) {
        return POP_CDNFOUNDATION_CDN_UPLOADS_URI;
    }

    return $domain;
}
