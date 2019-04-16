<?php
namespace PoP\CMSModel;

define('GD_DATALOADER_PAGE', 'page');

class Dataloader_Page extends Dataloader_PostBase
{
    use Dataloader_SingleTrait, Dataloader_PageTrait;

    public function getName()
    {
        return GD_DATALOADER_PAGE;
    }
}
    

/**
 * Initialize
 */
new Dataloader_Page();
