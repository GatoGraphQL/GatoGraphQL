<?php

declare(strict_types=1);

namespace PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\TypeAPIs;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface CustomPostUserTypeAPIInterface
{
    /**
     * Get the author of the Custom Post
     */
    public function getAuthorID(string | int | object $objectOrID);
}
