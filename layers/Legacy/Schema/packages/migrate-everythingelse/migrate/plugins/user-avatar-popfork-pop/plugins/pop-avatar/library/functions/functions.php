<?php
use PoP\Hooks\Facades\HooksAPIFacade;

// Connect the hooks from the provider plugin and the API
HooksAPIFacade::getInstance()->addFilter('gd_avatar_default', 'getDefaultAvatar', 1, 5);
