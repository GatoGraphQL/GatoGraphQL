<?php
namespace PoP\CMSModel;

define('GD_DATALOADER_TAG', 'tag');

class Dataloader_Tag extends Dataloader_TagBase
{
    public function getName()
    {
        return GD_DATALOADER_TAG;
    }
    
    public function getDbobjectIds($data_properties)
    {
        $vars = \PoP\Engine\Engine_Vars::getVars();
        return array($vars['global-state']['queried-object-id']);
    }
}
    

/**
 * Initialize
 */
new Dataloader_Tag();
