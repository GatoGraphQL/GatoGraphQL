<?php

namespace PoPSchema\Comments\WP;

use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\TypeDataResolvers\APITypeDataResolverTrait;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;

class FunctionAPI extends \PoPSchema\Comments\FunctionAPI_Base
{
    use APITypeDataResolverTrait;

    protected $cmsToPoPCommentStatusConversion = [
        'approve' => \PoPSchema\Comments\Constants\Status::APPROVED,
        'hold' => \PoPSchema\Comments\Constants\Status::ONHOLD,
        'spam' => \PoPSchema\Comments\Constants\Status::SPAM,
        'trash' => \PoPSchema\Comments\Constants\Status::TRASH,
    ];
    protected $popToCMSCommentStatusConversion;

    public function __construct()
    {
        parent::__construct();

        $this->popToCMSCommentStatusConversion = array_flip($this->cmsToPoPCommentStatusConversion);
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
    public function getComments($query, array $options = []): array
    {
        if ($return_type = $options['return-type'] ?? null) {
            if ($return_type == ReturnTypes::IDS) {
                $query['fields'] = 'ids';
            }
        }

        // Accept field atts to filter the API fields
        $this->maybeFilterDataloadQueryArgs($query, $options);

        // Convert the parameters
        if (isset($query['status'])) {
            $query['status'] = $this->convertCommentStatusFromPoPToCMS($query['status']);
        }
        if (isset($query['include'])) {
            $query['comment__in'] = $query['include'];
            unset($query['include']);
        }
        if (isset($query['customPostID'])) {
            $query['post_id'] = $query['customPostID'];
            unset($query['customPostID']);
        }
        if (\PoPSchema\Comments\Server::mustHaveUserAccountToAddComment()) {
            if (isset($query['userID'])) {

                $query['user_id'] = $query['userID'];
                unset($query['userID']);
            }
            if (isset($query['authors'])) {

                // Only 1 author is accepted
                $query['user_id'] = $query['authors'][0];
                unset($query['authors']);
            }
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
            $query['number'] = $query['limit'];
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
        // Only comments, no trackbacks or pingbacks
        $query['type'] = 'comment';

        $query = HooksAPIFacade::getInstance()->applyFilters(
            'CMSAPI:comments:query',
            $query,
            $options
        );
        return (array) \get_comments($query);
    }
    public function getComment($comment_id)
    {
        return \get_comment($comment_id);
    }
    public function getCommentNumber($post_id): int
    {
        return (int) \get_comments_number($post_id);
    }
    public function areCommentsOpen($post_id): bool
    {
        return \comments_open($post_id);
    }
}

/**
 * Initialize
 */
new FunctionAPI();
