<?php
namespace PoP\CMSModel;

abstract class Dataloader_UserBase extends \PoP\Engine\QueryDataDataloader
{
    public function getDataquery()
    {
        return GD_DATAQUERY_USER;
    }
    
    public function getDatabaseKey()
    {
        return GD_DATABASE_KEY_USERS;
    }

    public function getFieldprocessor()
    {
        return GD_DATALOAD_FIELDPROCESSOR_USERS;
    }
    
    public function executeGetData($ids)
    {
        if ($ids) {
            $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
            $ret = array();
            foreach ($ids as $user_id) {
                $ret[] = $cmsapi->getUserBy('id', $user_id);
            }
            return $ret;
        }
        
        return array();
    }
}
