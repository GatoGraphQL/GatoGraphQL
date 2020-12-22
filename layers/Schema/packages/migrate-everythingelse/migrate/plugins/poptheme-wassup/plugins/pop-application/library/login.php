<?php
use PoP\Hooks\Facades\HooksAPIFacade;

// When the theme loads successfully, it will override the default login/logout/change-pwd pages
HooksAPIFacade::getInstance()->addFilter('enableLoginApplicationScreens', '__return_true');
