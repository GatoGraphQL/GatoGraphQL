<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

HooksAPIFacade::getInstance()->addFilter('PoP_ApplicationProcessors_ResourceLoader_Hooks:single-resources:independent-cats', 'popEventlinksAddEventlinkCategory');
