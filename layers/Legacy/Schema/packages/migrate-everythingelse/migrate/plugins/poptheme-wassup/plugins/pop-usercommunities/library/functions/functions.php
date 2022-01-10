<?php
use PoP\ComponentModel\State\ApplicationState;
use PoP\Hooks\Facades\HooksAPIFacade;

HooksAPIFacade::getInstance()->addFilter('PoP_Module_Processor_PageTabPageSections:getModuleExtraInterceptUrls', 'gdUreAddSourceParamPagesections');
HooksAPIFacade::getInstance()->addFilter('PoP_Module_Processor_TabPanePageSections:getModuleExtraInterceptUrls', 'gdUreAddSourceParamPagesections');
function gdUreAddSourceParamPagesections($url)
{
    $vars = ApplicationState::getVars();
    if (\PoP\Root\App::getState(['routing', 'is-user'])) {
        $author = \PoP\Root\App::getState(['routing', 'queried-object-id']);
        return gdUreAddSourceParam($url, $author);
    }

    return $url;
}
