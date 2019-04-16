<?php
namespace PoP\CMSModel;

define('GD_DATAQUERY_TAG', 'tag');

class DataQuery_Tag extends \PoP\Engine\DataQueryBase
{
    public function getName()
    {
        return GD_DATAQUERY_TAG;
    }

    public function getNonCacheableRoute()
    {
        return POP_CMSMODEL_ROUTE_LOADERS_TAGS_FIELDS;
    }
    public function getCacheableRoute()
    {
        return POP_CMSMODEL_ROUTE_LOADERS_TAGS_LAYOUTS;
    }
    public function getObjectidFieldname()
    {
        return POP_INPUTNAME_TAGID;
    }
}
    
/**
 * Initialize
 */
new DataQuery_Tag();
