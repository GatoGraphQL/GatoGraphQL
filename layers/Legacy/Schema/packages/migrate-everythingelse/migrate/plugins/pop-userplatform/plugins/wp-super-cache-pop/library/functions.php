<?php

// Whenever a user is created or updated
\PoP\Root\App::getHookManager()->addAction('gd_createupdate_user:additionals', 'gdWpCacheUserEdit', 0, 0);
