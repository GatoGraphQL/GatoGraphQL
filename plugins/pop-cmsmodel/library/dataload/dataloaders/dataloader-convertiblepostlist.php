<?php
namespace PoP\CMSModel;

define('GD_DATALOADER_CONVERTIBLEPOSTLIST', 'convertible-post-list');

class Dataloader_ConvertiblePostList extends Dataloader_PostListBase
{
    public function getName()
    {
        return GD_DATALOADER_CONVERTIBLEPOSTLIST;
    }

    public function getFieldprocessor()
    {
        return GD_DATALOAD_CONVERTIBLEFIELDPROCESSOR_POSTS;
    }
}

/**
 * Initialize
 */
new Dataloader_ConvertiblePostList();
