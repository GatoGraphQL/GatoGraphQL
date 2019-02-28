<?php
namespace PoP\CMSModel;

define('GD_DATALOADER_USERLIST', 'user-list');

class Dataloader_UserList extends Dataloader_UserListBase
{
    public function getName()
    {
        return GD_DATALOADER_USERLIST;
    }
}

/**
 * Initialize
 */
new Dataloader_UserList();
