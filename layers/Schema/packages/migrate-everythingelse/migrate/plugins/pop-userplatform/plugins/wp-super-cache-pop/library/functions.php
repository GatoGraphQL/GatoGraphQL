<?php
use PoP\Hooks\Facades\HooksAPIFacade;

// Whenever a user is created or updated
HooksAPIFacade::getInstance()->addAction('gd_createupdate_user:additionals', 'gdWpCacheUserEdit', 0, 0);
