<?php
use PoP\Hooks\Facades\HooksAPIFacade;

HooksAPIFacade::getInstance()->addFilter('PoP_ApplicationProcessors_SPA_Hooks:backgroundurls:backgroundurl', 'popMultidomainQtransMaybeReplacelang', 10, 3);
HooksAPIFacade::getInstance()->addFilter('PoP_ApplicationProcessors_SPA_Hooks:backgroundurls:backgroundurl', 'popMultidomainMaybeReplacepath', 10, 3);
