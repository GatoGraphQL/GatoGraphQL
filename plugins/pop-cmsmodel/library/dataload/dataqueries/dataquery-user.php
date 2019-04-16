<?php
namespace PoP\CMSModel;

define('GD_DATAQUERY_USER', 'user');

class DataQuery_User extends \PoP\Engine\DataQueryBase
{
    public function getName()
    {
        return GD_DATAQUERY_USER;
    }

    public function getNonCacheableRoute()
    {
        return POP_CMSMODEL_ROUTE_LOADERS_USERS_FIELDS;
    }
    public function getCacheableRoute()
    {
        return POP_CMSMODEL_ROUTE_LOADERS_USERS_LAYOUTS;
    }
    public function getObjectidFieldname()
    {
        return POP_INPUTNAME_USERID;
    }
}
    
/**
 * Initialize
 */
new DataQuery_User();
