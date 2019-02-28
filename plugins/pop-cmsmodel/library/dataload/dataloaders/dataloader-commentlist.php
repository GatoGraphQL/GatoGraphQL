<?php
namespace PoP\CMSModel;

define('GD_DATALOADER_COMMENTLIST', 'comments-list');

class Dataloader_CommentList extends Dataloader_CommentListBase
{
    public function getName()
    {
        return GD_DATALOADER_COMMENTLIST;
    }

    public function getQuery($query_args)
    {
        $query = parent::getQuery($query_args);

        $query['status'] = 'approve';
        $query['type'] = 'comment'; // Only comments, no trackbacks or pingbacks
        $query['post_id'] = $query_args[GD_URLPARAM_POSTID];

        return $query;
    }
    
    public function executeQuery($query)
    {
        $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
        return $cmsapi->getComments($query);
    }

    public function executeQueryIds($query)
    {
        $ret = array();
        $comments = $this->executeQuery($query);
        foreach ($comments as $comment) {
            $ret[] = $comment->comment_ID;
        }

        return $ret;
    }
}
    

/**
 * Initialize
 */
new Dataloader_CommentList();
