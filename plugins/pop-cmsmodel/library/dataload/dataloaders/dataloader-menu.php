<?php
namespace PoP\CMSModel;

define('GD_DATALOADER_MENU', 'menu');

class Dataloader_Menu extends Dataloader_MenuBase
{
    public function getName()
    {
        return GD_DATALOADER_MENU;
    }
    
    public function getDbobjectIds($data_properties)
    {
        $query_args = $data_properties[GD_DATALOAD_QUERYARGS];
    
        $menu = $query_args['menu'];

        $cmsresolver = \PoP\CMS\ObjectPropertyResolver_Factory::getInstance();
        $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
        $locations = $cmsapi->getNavMenuLocations();
        $menu_object = $cmsapi->wpGetNavMenuObject($locations[$menu]);

        return array($cmsresolver->getMenuObjectTermId($menu_object));
    }
}

/**
 * Initialize
 */
new Dataloader_Menu();
