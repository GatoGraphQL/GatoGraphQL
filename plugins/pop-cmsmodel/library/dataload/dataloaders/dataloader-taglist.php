<?php
namespace PoP\CMSModel;

define('GD_DATALOADER_TAGLIST', 'tag-list');

class Dataloader_TagList extends Dataloader_TagListBase
{
    public function getName()
    {
        return GD_DATALOADER_TAGLIST;
    }
}

/**
 * Initialize
 */
new Dataloader_TagList();
