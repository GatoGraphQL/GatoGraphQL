<?php

// When updating My Communities
\PoP\Root\App::getHookManager()->addAction('gd_update_mycommunities:update', 'gdWpCacheUserEdit', 0, 0);

// When updating a user membership
\PoP\Root\App::getHookManager()->addAction('GD_EditMembership:update', 'gdWpCacheUserEdit', 0, 0);
