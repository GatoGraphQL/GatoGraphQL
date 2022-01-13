<?php

// When the theme loads successfully, it will override the default login/logout/change-pwd pages
\PoP\Root\App::addFilter('enableLoginApplicationScreens', '__return_true');
