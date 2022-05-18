<?php

\PoP\Root\App::addFilter('PoP_ApplicationProcessors_Utils:initializedomain:modules', 'addDomainstylesModule');
function addDomainstylesModule($componentVariations)
{
    $componentVariations[] = [PoP_Module_Processor_DomainStyleCodes::class, PoP_Module_Processor_DomainStyleCodes::MODULE_CODE_DOMAINSTYLES];
    return $componentVariations;
}
