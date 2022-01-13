<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

HooksAPIFacade::getInstance()->addFilter('PoP_ApplicationProcessors_SPA_Hooks:backgroundurls:backgroundurl', 'popMultidomainQtransMaybeReplacelang', 10, 3);
HooksAPIFacade::getInstance()->addFilter('PoP_ApplicationProcessors_SPA_Hooks:backgroundurls:backgroundurl', 'popMultidomainMaybeReplacepath', 10, 3);
