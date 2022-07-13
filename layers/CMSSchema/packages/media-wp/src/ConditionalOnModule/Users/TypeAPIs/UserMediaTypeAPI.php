<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaWP\ConditionalOnModule\Users\TypeAPIs;

use PoPCMSSchema\Media\ConditionalOnModule\Users\TypeAPIs\UserMediaTypeAPIInterface;

use function get_post;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class UserMediaTypeAPI implements UserMediaTypeAPIInterface
{
    public function getMediaAuthorId(string|int|object $mediaObjectOrID): string|int|null
    {
        if (is_object($mediaObjectOrID)) {
            $media = $mediaObjectOrID;
        } else {
            $media = get_post($mediaObjectOrID);
        }
        return $media->post_author;
    }
}
