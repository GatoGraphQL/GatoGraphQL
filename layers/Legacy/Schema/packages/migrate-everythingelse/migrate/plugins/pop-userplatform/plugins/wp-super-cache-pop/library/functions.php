<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

// Whenever a user is created or updated
HooksAPIFacade::getInstance()->addAction('gd_createupdate_user:additionals', 'gdWpCacheUserEdit', 0, 0);
