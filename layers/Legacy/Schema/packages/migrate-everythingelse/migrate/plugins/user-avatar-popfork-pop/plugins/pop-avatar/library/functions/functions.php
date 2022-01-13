<?php

// Connect the hooks from the provider plugin and the API
\PoP\Root\App::getHookManager()->addFilter('gd_avatar_default', 'getDefaultAvatar', 1, 5);
