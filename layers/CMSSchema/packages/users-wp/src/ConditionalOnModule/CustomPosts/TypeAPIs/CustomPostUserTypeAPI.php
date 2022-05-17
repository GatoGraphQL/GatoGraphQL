<?php

declare(strict_types=1);

namespace PoPCMSSchema\UsersWP\ConditionalOnModule\CustomPosts\TypeAPIs;

use PoPCMSSchema\CustomPostsWP\TypeAPIs\CustomPostTypeAPIHelpers;
use PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\TypeAPIs\CustomPostUserTypeAPIInterface;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class CustomPostUserTypeAPI implements CustomPostUserTypeAPIInterface
{
    public function getAuthorID(string | int | object $customPostObjectOrID)
    {
        list(
            $customPost,
            $customPostID,
        ) = CustomPostTypeAPIHelpers::getCustomPostObjectAndID($customPostObjectOrID);
        return $customPost->post_author;
    }
}
