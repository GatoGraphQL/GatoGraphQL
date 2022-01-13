<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

HooksAPIFacade::getInstance()->addFilter('popcomponent:coauthors:supportedposttypes', 'gdEmAddEventPosttype');
