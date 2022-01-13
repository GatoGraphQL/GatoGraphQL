<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

\PoP\Root\App::getHookManager()->addFilter('PoP_ApplicationProcessors_ResourceLoader_Hooks:single-resources:independent-cats', 'popEventlinksAddEventlinkCategory');
