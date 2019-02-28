<?php
namespace PoP\CMSModel;

define('GD_DATALOADER_SINGLE', 'single');

class Dataloader_Single extends Dataloader_PostBase
{
    use Dataloader_SingleTrait;

    public function getName()
    {
        return GD_DATALOADER_SINGLE;
    }

    // /**
    //     * Function to override
    //     */
    // function executeGetData($ids) {
    
    //     $vars = \PoP\Engine\Engine_Vars::getVars();
    //     $post = $vars['global-state']['queried-object'];
    //     return array($post);
    // }
}
    

/**
 * Initialize
 */
new Dataloader_Single();
