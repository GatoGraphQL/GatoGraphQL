<?php
namespace PoP\CMSModel;

define('GD_DATAQUERY_COMMENT', 'comment');

class DataQuery_Comment extends \PoP\Engine\DataQueryBase
{
    public function getName()
    {
        return GD_DATAQUERY_COMMENT;
    }

    public function getNoncacheablePage()
    {
        return POP_CMSMODEL_PAGE_LOADERS_COMMENTS_FIELDS;
    }
    public function getCacheablePage()
    {
        return POP_CMSMODEL_PAGE_LOADERS_COMMENTS_LAYOUTS;
    }
    public function getObjectidFieldname()
    {
        return POP_INPUTNAME_COMMENTID;
    }
}
    
/**
 * Initialize
 */
new DataQuery_Comment();
