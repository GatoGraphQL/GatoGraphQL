<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

HooksAPIFacade::getInstance()->addFilter('PoP_ApplicationProcessors_UserLogin_Hooks:backgroundurls:loggedinuser_data', 'popMultidomainQtransMaybeReplacelang', 10, 3);
HooksAPIFacade::getInstance()->addFilter('PoP_ApplicationProcessors_UserLogin_Hooks:backgroundurls:loggedinuser_data', 'popMultidomainMaybeReplacepath', 10, 3);
