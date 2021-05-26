<?php

declare(strict_types=1);

namespace PoPSchema\MediaWP\ConditionalOnComponent\Users\TypeAPIs;

use PoPSchema\Media\ConditionalOnComponent\Users\TypeAPIs\MediaTypeAPIInterface;
use function get_post;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class MediaTypeAPI implements MediaTypeAPIInterface
{
    public function getMediaAuthorId(string | int | object $mediaObjectOrID): string | int | null
    {
        if (is_object($mediaObjectOrID)) {
            $media = $mediaObjectOrID;
        } else {
            $media = get_post($mediaObjectOrID);
        }
        return $media->post_author;
    }
}
