<?php
namespace PoP\CMSModel;

define('GD_DATALOADER_CONVERTIBLEUSERLIST', 'convertible-user-list');

class Dataloader_ConvertibleUserList extends Dataloader_UserListBase
{
    public function getName()
    {
        return GD_DATALOADER_CONVERTIBLEUSERLIST;
    }

    public function getFieldprocessor()
    {
        return GD_DATALOAD_CONVERTIBLEFIELDPROCESSOR_USERS;
    }
}

/**
 * Initialize
 */
new Dataloader_ConvertibleUserList();
