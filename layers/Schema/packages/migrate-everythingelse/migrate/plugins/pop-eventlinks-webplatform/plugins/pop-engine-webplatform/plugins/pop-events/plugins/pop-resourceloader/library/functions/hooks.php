<?php
use PoP\Hooks\Facades\HooksAPIFacade;

HooksAPIFacade::getInstance()->addFilter('PoP_ApplicationProcessors_ResourceLoader_Hooks:single-resources:independent-cats', 'popEventlinksAddEventlinkCategory');
