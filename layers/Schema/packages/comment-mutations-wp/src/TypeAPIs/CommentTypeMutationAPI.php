<?php

declare(strict_types=1);

namespace PoPSchema\CommentMutationsWP\TypeAPIs;

use PoP\ComponentModel\Error\Error;
use PoP\BasicService\BasicServiceTrait;
use PoPSchema\CommentMutations\TypeAPIs\CommentTypeMutationAPIInterface;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class CommentTypeMutationAPI implements CommentTypeMutationAPIInterface
{
    use BasicServiceTrait;

    public function insertComment(array $comment_data): string | int | Error
    {
        // Convert the parameters
        if (isset($comment_data['userID'])) {
            $comment_data['user_id'] = $comment_data['userID'];
            unset($comment_data['userID']);
        }
        if (isset($comment_data['author'])) {
            $comment_data['comment_author'] = $comment_data['author'];
            unset($comment_data['author']);
        }
        if (isset($comment_data['authorEmail'])) {
            $comment_data['comment_author_email'] = $comment_data['authorEmail'];
            unset($comment_data['authorEmail']);
        }
        if (isset($comment_data['authorURL'])) {
            $comment_data['comment_author_url'] = $comment_data['authorURL'];
            unset($comment_data['authorURL']);
        }
        if (isset($comment_data['authorIP'])) {
            $comment_data['comment_author_IP'] = $comment_data['authorIP'];
            unset($comment_data['authorIP']);
        }
        if (isset($comment_data['agent'])) {
            $comment_data['comment_agent'] = $comment_data['agent'];
            unset($comment_data['agent']);
        }
        if (isset($comment_data['content'])) {
            $comment_data['comment_content'] = $comment_data['content'];
            unset($comment_data['content']);
        }
        if (isset($comment_data['parent'])) {
            $comment_data['comment_parent'] = $comment_data['parent'];
            unset($comment_data['parent']);
        }
        if (isset($comment_data['customPostID'])) {
            $comment_data['comment_post_ID'] = $comment_data['customPostID'];
            unset($comment_data['customPostID']);
        }
        $commentID = \wp_insert_comment($comment_data);
        if ($commentID === false) {
            return new Error(
                'insert-comment-error',
                $this->__('Could not create the comment', 'comment-mutations-wp')
            );
        }
        return $commentID;
    }
}
