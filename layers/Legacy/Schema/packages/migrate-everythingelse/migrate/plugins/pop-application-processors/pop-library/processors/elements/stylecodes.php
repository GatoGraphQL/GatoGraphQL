<?php

class PoP_Module_Processor_DomainStyleCodes extends PoP_Module_Processor_StyleCodesBase
{
    public final const COMPONENT_CODE_DOMAINSTYLES = 'code-domainstyles';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_CODE_DOMAINSTYLES],
        );
    }

    public function getCode(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_CODE_DOMAINSTYLES:
                // Print all the inline styles for this domain
                $domain = PoP_Application_Utils::getRequestDomain();

                // Allow PoP Theme Wassup to add the background color styles
                return \PoP\Root\App::applyFilters(
                    'PoP_Module_Processor_DomainCodes:code:styles',
                    getLoggedinDomainStyles($domain), // Logged in the domain styles
                    $domain
                );
        }
    
        return parent::getCode($component, $props);
    }
}


