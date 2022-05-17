<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentsWP\ConditionalOnModule\Users\TypeAPIs;

use PoPCMSSchema\Comments\ConditionalOnModule\Users\TypeAPIs\CommentTypeAPIInterface;
use WP_Comment;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class CommentTypeAPI implements CommentTypeAPIInterface
{
    public function getCommentUserId(object $comment): string | int | null
    {
        /** @var WP_Comment */
        $comment = $comment;
        // Watch out! If there is no user ID, it stores it with ID "0"
        $userID = (int)$comment->user_id;
        if ($userID === 0) {
            return null;
        }
        return $userID;
    }
}
