<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

\PoP\Root\App::getHookManager()->addFilter('popcomponent:coauthors:supportedposttypes', 'gdEmAddEventPosttype');
