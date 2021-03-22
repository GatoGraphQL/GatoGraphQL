<?php

declare(strict_types=1);

namespace PoPSchema\UsersWP\Conditional\CustomPosts\TypeAPIs;

use PoPSchema\CustomPostsWP\TypeAPIs\CustomPostTypeAPIHelpers;
use PoPSchema\Users\Conditional\CustomPosts\TypeAPIs\CustomPostUserTypeAPIInterface;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class CustomPostUserTypeAPI implements CustomPostUserTypeAPIInterface
{
    public function getAuthorID(mixed $customPostObjectOrID)
    {
        list(
            $customPost,
            $customPostID,
        ) = CustomPostTypeAPIHelpers::getCustomPostObjectAndID($customPostObjectOrID);
        return $customPost->post_author;
    }
}
