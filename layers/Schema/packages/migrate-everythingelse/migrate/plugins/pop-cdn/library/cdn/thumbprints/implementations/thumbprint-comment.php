<?php
use PoP\LooseContracts\Facades\NameResolverFacade;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\Comments\Facades\CommentTypeAPIFacade;

define('POP_CDN_THUMBPRINT_COMMENT', 'comment');

class PoP_CDN_Thumbprint_Comment extends PoP_CDN_ThumbprintBase
{
    public function getName()
    {
        return POP_CDN_THUMBPRINT_COMMENT;
    }

    public function getQuery()
    {
        return array(
            // 'fields' => 'ids',
            'limit' => 1,
            'status' => \PoPSchema\Comments\Constants\Status::APPROVED,
            // 'type' => 'comment', // Only comments, no trackbacks or pingbacks
            'order' =>  'DESC',
            'orderby' => NameResolverFacade::getInstance()->getName('popcms:dbcolumn:orderby:comments:date'),
        );
    }

    public function executeQuery($query, array $options = [])
    {
        $commentTypeAPI = CommentTypeAPIFacade::getInstance();
        $options['return-type'] = ReturnTypes::IDS;
        return $commentTypeAPI->getComments($query, $options);
    }

    public function getTimestamp($comment_id)
    {
        return get_comment_date('U', $comment_id);
    }
}

/**
 * Initialize
 */
new PoP_CDN_Thumbprint_Comment();
