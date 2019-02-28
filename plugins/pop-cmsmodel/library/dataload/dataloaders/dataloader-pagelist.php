<?php
namespace PoP\CMSModel;

define('GD_DATALOADER_PAGELIST', 'page-list');

class Dataloader_PageList extends Dataloader_PageListBase
{
    public function getName()
    {
        return GD_DATALOADER_PAGELIST;
    }
}

/**
 * Initialize
 */
new Dataloader_PageList();
