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

        $query['status'] = POP_COMMENTSTATUS_APPROVED;
        // $query['type'] = 'comment'; // Only comments, no trackbacks or pingbacks
        $query['post-id'] = $query_args[GD_URLPARAM_COMMENTPOSTID];

        return $query;
    }
    
    public function executeQuery($query, array $options = [])
    {
        $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
        return $cmsapi->getComments($query, $options);
    }

    public function executeQueryIds($query)
    {
        $options = [
            'return-type' => POP_RETURNTYPE_IDS,
        ];
        return $this->executeQuery($query, $options);
    }
}
    

/**
 * Initialize
 */
new Dataloader_CommentList();
