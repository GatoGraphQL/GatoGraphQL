<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\State\ApplicationState;

HooksAPIFacade::getInstance()->addFilter('PoP_Module_Processor_PageTabPageSections:getModuleExtraInterceptUrls', 'gdUreAddSourceParamPagesections');
HooksAPIFacade::getInstance()->addFilter('PoP_Module_Processor_TabPanePageSections:getModuleExtraInterceptUrls', 'gdUreAddSourceParamPagesections');
function gdUreAddSourceParamPagesections($url)
{
    $vars = ApplicationState::getVars();
    if ($vars['routing-state']['is-user']) {
        $author = $vars['routing-state']['queried-object-id'];
        return gdUreAddSourceParam($url, $author);
    }

    return $url;
}
