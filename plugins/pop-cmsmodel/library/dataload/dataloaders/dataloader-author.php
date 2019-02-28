<?php
namespace PoP\CMSModel;

define('GD_DATALOADER_AUTHOR', 'author');

class Dataloader_Author extends Dataloader_UserBase
{
    public function getName()
    {
        return GD_DATALOADER_AUTHOR;
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
new Dataloader_Author();
