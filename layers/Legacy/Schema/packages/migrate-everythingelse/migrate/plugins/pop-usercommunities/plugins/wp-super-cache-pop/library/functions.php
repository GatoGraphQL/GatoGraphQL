<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

// When updating My Communities
HooksAPIFacade::getInstance()->addAction('gd_update_mycommunities:update', 'gdWpCacheUserEdit', 0, 0);

// When updating a user membership
HooksAPIFacade::getInstance()->addAction('GD_EditMembership:update', 'gdWpCacheUserEdit', 0, 0);
