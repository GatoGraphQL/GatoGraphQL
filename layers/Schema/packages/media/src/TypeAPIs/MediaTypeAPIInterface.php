<?php

declare(strict_types=1);

namespace PoPSchema\Media\TypeAPIs;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface MediaTypeAPIInterface
{
    /**
     * Indicates if the passed object is of type Media
     *
     * @param [type] $object
     * @return boolean
     */
    public function isInstanceOfMediaType($object): bool;
}
