<?php

declare(strict_types=1);

namespace PoPSchema\MediaWP\TypeAPIs;

use WP_Post;
use PoPSchema\Media\TypeAPIs\MediaTypeAPIInterface;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class MediaTypeAPI implements MediaTypeAPIInterface
{
    /**
     * Indicates if the passed object is of type Media
     */
    public function isInstanceOfMediaType(object $object): bool
    {
        return ($object instanceof WP_Post) && $object->post_type == 'attachment';
    }
}
