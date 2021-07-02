<?php
use PoP\Hooks\Facades\HooksAPIFacade;

HooksAPIFacade::getInstance()->addFilter('popcomponent:coauthors:supportedposttypes', 'gdEmAddEventPosttype');
