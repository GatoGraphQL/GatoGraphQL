<?php
namespace PoP\CMSModel;

define('GD_DATALOADER_HOMESTATICPAGE', 'homestaticpage');

class Dataloader_HomeStaticPage extends Dataloader_PostBase
{
    public function getName()
    {
        return GD_DATALOADER_HOMESTATICPAGE;
    }

    public function getDbobjectIds($data_properties)
    {
        $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
        if ($page_id = $cmsapi->getHomeStaticPage()) {
            return array($page_id);
        }
        return array();
    }
}
    

/**
 * Initialize
 */
new Dataloader_HomeStaticPage();
