<?php
use PoP\ComponentModel\State\ApplicationState;

\PoP\Root\App::addFilter('PoP_Module_Processor_PageTabPageSections:getComponentExtraInterceptURLs', 'gdUreAddSourceParamPagesections');
\PoP\Root\App::addFilter('PoP_Module_Processor_TabPanePageSections:getComponentExtraInterceptURLs', 'gdUreAddSourceParamPagesections');
function gdUreAddSourceParamPagesections($url)
{
    if (\PoP\Root\App::getState(['routing', 'is-user'])) {
        $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
        return gdUreAddSourceParam($url, $author);
    }

    return $url;
}
