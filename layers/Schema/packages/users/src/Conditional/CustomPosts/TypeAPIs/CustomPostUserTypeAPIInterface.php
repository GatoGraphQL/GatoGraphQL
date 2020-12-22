<?php

declare(strict_types=1);

namespace PoPSchema\Users\Conditional\CustomPosts\TypeAPIs;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface CustomPostUserTypeAPIInterface
{
    /**
     * Get the author of the Custom Post
     *
     * @param [type] $objectOrID
     * @return boolean
     */
    public function getAuthorID($objectOrID);
}
