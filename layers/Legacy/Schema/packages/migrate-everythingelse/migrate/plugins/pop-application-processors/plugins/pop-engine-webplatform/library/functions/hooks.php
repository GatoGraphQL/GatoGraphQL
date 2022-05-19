<?php

\PoP\Root\App::addFilter('PoP_ApplicationProcessors_Utils:initializedomain:modules', 'addDomainstylesModule');
function addDomainstylesModule($components)
{
    $components[] = [PoP_Module_Processor_DomainStyleCodes::class, PoP_Module_Processor_DomainStyleCodes::COMPONENT_CODE_DOMAINSTYLES];
    return $components;
}
