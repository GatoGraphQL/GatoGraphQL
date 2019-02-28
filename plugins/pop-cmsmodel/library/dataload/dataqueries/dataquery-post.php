<?php
namespace PoP\CMSModel;

define('GD_DATAQUERY_POST', 'post');

class DataQuery_Post extends \PoP\Engine\DataQueryBase
{
    public function getName()
    {
        return GD_DATAQUERY_POST;
    }

    public function getNoncacheablePage()
    {
        return POP_CMSMODEL_PAGE_LOADERS_POSTS_FIELDS;
    }
    public function getCacheablePage()
    {
        return POP_CMSMODEL_PAGE_LOADERS_POSTS_LAYOUTS;
    }
    public function getObjectidFieldname()
    {
        return POP_INPUTNAME_POSTID;
    }
}
    
/**
 * Initialize
 */
new DataQuery_Post();
