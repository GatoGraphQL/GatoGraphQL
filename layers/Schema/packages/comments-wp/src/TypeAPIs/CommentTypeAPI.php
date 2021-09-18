<?php

declare(strict_types=1);

namespace PoPSchema\CommentsWP\TypeAPIs;

use PoP\Hooks\HooksAPIInterface;
use PoPSchema\Comments\Constants\CommentTypes;
use PoPSchema\Comments\Constants\CommentStatus;
use PoPSchema\Comments\TypeAPIs\CommentTypeAPIInterface;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use WP_Comment;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class CommentTypeAPI implements CommentTypeAPIInterface
{
    public const HOOK_QUERY = __CLASS__ . ':query';

    protected array $cmsToPoPCommentStatusConversion = [
        'approve' => CommentStatus::APPROVE,
        'hold' => CommentStatus::HOLD,
        'spam' => CommentStatus::SPAM,
        'trash' => CommentStatus::TRASH,
    ];

    protected array $popToCMSCommentStatusConversion;

    public function __construct(
        protected HooksAPIInterface $hooksAPI,
    ) {
        $this->popToCMSCommentStatusConversion = array_flip($this->cmsToPoPCommentStatusConversion);
    }

    /**
     * Indicates if the passed object is of type Comment
     */
    public function isInstanceOfCommentType(object $object): bool
    {
        return $object instanceof WP_Comment;
    }

    protected function convertCommentStatusFromCMSToPoP($status)
    {
        // Convert from the CMS status to PoP's one
        return $this->cmsToPoPCommentStatusConversion[$status];
    }
    protected function convertCommentStatusFromPoPToCMS($status)
    {
        // Convert from the CMS status to PoP's one
        return $this->popToCMSCommentStatusConversion[$status];
    }
    public function getComments(array $query, array $options = []): array
    {
        $query = $this->convertCommentsQuery($query, $options);
        return (array) \get_comments($query);
    }
    protected function convertCommentsQuery(array $query, array $options): array
    {
        if (($options[QueryOptions::RETURN_TYPE] ?? null) === ReturnTypes::IDS) {
            $query['fields'] = 'ids';
        }

        // Convert the parameters
        if (isset($query['status'])) {
            if (is_array($query['status'])) {
                $query['status'] = array_map(
                    [$this, 'convertCommentStatusFromPoPToCMS'],
                    $query['status']
                );
            } else {
                $query['status'] = $this->convertCommentStatusFromPoPToCMS($query['status']);
            }
        }
        if (isset($query['types'])) {
            $query['type__in'] = $query['types'];
            unset($query['types']);
        }
        if (!isset($query['type']) && !isset($query['type__in'])) {
            // Only comments, no trackbacks or pingbacks
            $query['type'] = CommentTypes::COMMENT;
        }
        if (isset($query['include'])) {
            // It can be an array or a string
            $query['comment__in'] = is_array($query['include']) ? $query['include'] : [$query['include']];
            unset($query['include']);
        }
        if (isset($query['exclude-ids'])) {
            $query['comment__not_in'] = $query['exclude-ids'];
            unset($query['exclude-ids']);
        }
        if (isset($query['customPostID'])) {
            $query['post_id'] = $query['customPostID'];
            unset($query['customPostID']);
        }
        if (isset($query['customPostIDs'])) {
            $query['post__in'] = $query['customPostIDs'];
            unset($query['customPostIDs']);
        }
        if (isset($query['exclude-customPostIDs'])) {
            $query['post__not_in'] = $query['customPostIDs'];
            unset($query['customPostIDs']);
        }
        if (isset($query['custompost-types'])) {
            $query['post_type'] = $query['custompost-types'];
            unset($query['custompost-types']);
        }
        // Comment parent ID
        // Pass "0" to retrieve 1st layer of comments added to the post
        if (isset($query['parent-id'])) {
            $query['parent'] = $query['parent-id'];
            unset($query['parent-id']);
        }
        if (isset($query['parent-ids'])) {
            $query['parent__in'] = $query['parent-ids'];
            unset($query['parent-ids']);
        }
        if (isset($query['exclude-parent-ids'])) {
            $query['parent__not_in'] = $query['exclude-parent-ids'];
            unset($query['exclude-parent-ids']);
        }

        if (isset($query['order'])) {
            // Same param name, so do nothing
        }
        if (isset($query['orderby'])) {
            // Same param name, so do nothing
            // This param can either be a string or an array. Eg:
            // $query['orderby'] => array('date' => 'DESC', 'title' => 'ASC');
        }
        // For the comments, if there's no limit then it brings all results
        if (isset($query['limit'])) {
            $limit = (int) $query['limit'];
            // To bring all results, must use "number => 0" instead of -1
            $query['number'] = ($limit == -1) ? 0 : $limit;
            unset($query['limit']);
        }
        if (isset($query['search'])) {
            // Same param name, so do nothing
        }
        // Filtering by date: Instead of operating on the query, it does it through filter 'posts_where'
        if (isset($query['date-from'])) {
            $query['date_query'][] = [
                'after' => $query['date-from'],
                'inclusive' => false,
            ];
            unset($query['date-from']);
        }
        if (isset($query['date-from-inclusive'])) {
            $query['date_query'][] = [
                'after' => $query['date-from-inclusive'],
                'inclusive' => true,
            ];
            unset($query['date-from-inclusive']);
        }
        if (isset($query['date-to'])) {
            $query['date_query'][] = [
                'before' => $query['date-to'],
                'inclusive' => false,
            ];
            unset($query['date-to']);
        }
        if (isset($query['date-to-inclusive'])) {
            $query['date_query'][] = [
                'before' => $query['date-to-inclusive'],
                'inclusive' => true,
            ];
            unset($query['date-to-inclusive']);
        }

        $query = $this->hooksAPI->applyFilters(
            self::HOOK_QUERY,
            $query,
            $options
        );
        return $query;
    }
    public function getCommentCount(array $query, array $options = []): int
    {
        $query = $this->convertCommentsQuery($query, $options);
        $query['number'] = 0;
        unset($query['offset']);
        $query['count'] = true;
        /** @var int */
        $count = \get_comments($query);
        return $count;
    }
    public function getComment(string | int $comment_id): ?object
    {
        return \get_comment($comment_id);
    }
    public function getCommentNumber(string | int $post_id): int
    {
        return (int) \get_comments_number($post_id);
    }
    public function areCommentsOpen(string | int $post_id): bool
    {
        return \comments_open($post_id);
    }

    public function getCommentContent(object $comment): string
    {
        return $this->hooksAPI->applyFilters(
            'comment_text',
            $this->getCommentPlainContent($comment)
        );
    }
    public function getCommentPlainContent(object $comment): string
    {
        /** @var WP_Comment */
        $comment = $comment;
        return $comment->comment_content;
    }
    public function getCommentPostId(object $comment): string | int
    {
        /** @var WP_Comment */
        $comment = $comment;
        return (int)$comment->comment_post_ID;
    }
    public function isCommentApproved(object $comment): bool
    {
        /** @var WP_Comment */
        $comment = $comment;
        return $comment->comment_approved == "1";
    }
    public function getCommentType(object $comment): string
    {
        /** @var WP_Comment */
        $comment = $comment;
        return $comment->comment_type;
    }
    public function getCommentStatus(object $comment): string
    {
        /** @var WP_Comment */
        $comment = $comment;
        if ($comment->comment_approved == "1") {
            return CommentStatus::APPROVE;
        } elseif ($comment->comment_approved == "spam") {
            return CommentStatus::SPAM;
        } elseif ($comment->comment_approved == "trash") {
            return CommentStatus::TRASH;
        };
        return CommentStatus::HOLD;
    }
    public function getCommentParent(object $comment): string | int | null
    {
        /** @var WP_Comment */
        $comment = $comment;
        // If it has no parent, it is assigned 0. In that case, return null
        if ($parent = $comment->comment_parent) {
            return (int)$parent;
        }
        return null;
    }
    public function getCommentDate(object $comment, bool $gmt = false): string
    {
        /** @var WP_Comment $comment*/
        return $gmt ? $comment->comment_date_gmt : $comment->comment_date;
    }
    public function getCommentId(object $comment): string | int
    {
        /** @var WP_Comment */
        $comment = $comment;
        return (int)$comment->comment_ID;
    }
    public function getCommentAuthorName(object $comment): string
    {
        /** @var WP_Comment */
        $comment = $comment;
        return $comment->comment_author;
    }
    public function getCommentAuthorEmail(object $comment): string
    {
        /** @var WP_Comment */
        $comment = $comment;
        return $comment->comment_author_email;
    }
    public function getCommentAuthorURL(object $comment): string
    {
        /** @var WP_Comment */
        $comment = $comment;
        return $comment->comment_author_url;
    }
}
