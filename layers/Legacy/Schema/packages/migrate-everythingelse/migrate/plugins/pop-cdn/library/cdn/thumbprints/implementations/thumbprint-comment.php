<?php
use PoP\LooseContracts\Facades\NameResolverFacade;
use PoPSchema\Comments\Facades\CommentTypeAPIFacade;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;

define('POP_CDN_THUMBPRINT_COMMENT', 'comment');

class PoP_CDN_Thumbprint_Comment extends PoP_CDN_ThumbprintBase
{
    public function getName(): string
    {
        return POP_CDN_THUMBPRINT_COMMENT;
    }

    public function getQuery()
    {
        return array(
            // 'fields' => 'ids',
            'limit' => 1,
            'order' =>  'DESC',
            'orderby' => NameResolverFacade::getInstance()->getName('popcms:dbcolumn:orderby:comments:date'),
        );
    }

    public function executeQuery($query, array $options = []): array
    {
        $commentTypeAPI = CommentTypeAPIFacade::getInstance();
        $options[QueryOptions::RETURN_TYPE] = ReturnTypes::IDS;
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
