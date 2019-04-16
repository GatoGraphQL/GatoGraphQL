<?php
namespace PoP\CMSModel;

trait Dataloader_SingleTrait
{
    public function getDbobjectIds($data_properties)
    {
    
        // Simply return the global $post ID.
        $vars = \PoP\Engine\Engine_Vars::getVars();
        return array($vars['routing-state']['queried-object-id']);
    }
}
