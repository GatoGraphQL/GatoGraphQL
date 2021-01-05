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
     *
     * @param [type] $object
     * @return boolean
     */
    public function isInstanceOfMediaType($object): bool
    {
        return ($object instanceof WP_Post) && $object->post_type == 'attachment';
    }
}
