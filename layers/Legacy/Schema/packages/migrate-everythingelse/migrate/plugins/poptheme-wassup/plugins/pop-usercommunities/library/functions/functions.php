<?php
use PoP\ComponentModel\State\ApplicationState;

\PoP\Root\App::getHookManager()->addFilter('PoP_Module_Processor_PageTabPageSections:getModuleExtraInterceptUrls', 'gdUreAddSourceParamPagesections');
\PoP\Root\App::getHookManager()->addFilter('PoP_Module_Processor_TabPanePageSections:getModuleExtraInterceptUrls', 'gdUreAddSourceParamPagesections');
function gdUreAddSourceParamPagesections($url)
{
    if (\PoP\Root\App::getState(['routing', 'is-user'])) {
        $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
        return gdUreAddSourceParam($url, $author);
    }

    return $url;
}
