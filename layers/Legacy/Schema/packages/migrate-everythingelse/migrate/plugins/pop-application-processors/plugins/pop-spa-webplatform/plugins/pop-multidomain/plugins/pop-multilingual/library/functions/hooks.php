<?php

\PoP\Root\App::getHookManager()->addFilter('PoP_ApplicationProcessors_SPA_Hooks:backgroundurls:backgroundurl', 'popMultidomainQtransMaybeReplacelang', 10, 3);
\PoP\Root\App::getHookManager()->addFilter('PoP_ApplicationProcessors_SPA_Hooks:backgroundurls:backgroundurl', 'popMultidomainMaybeReplacepath', 10, 3);
