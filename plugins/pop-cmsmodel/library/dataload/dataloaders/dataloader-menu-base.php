<?php
namespace PoP\CMSModel;

abstract class Dataloader_MenuBase extends \PoP\Engine\QueryDataDataloader
{
    public function getFieldprocessor()
    {
        return GD_DATALOAD_FIELDPROCESSOR_MENU;
    }

    public function getDatabaseKey()
    {
        return GD_DATABASE_KEY_MENUS;
    }
    
    public function executeGetData($ids)
    {
        $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
        $ret = array_map(array($cmsapi, 'wpGetNavMenuObject'), $ids);
        return $ret;
    }
}
