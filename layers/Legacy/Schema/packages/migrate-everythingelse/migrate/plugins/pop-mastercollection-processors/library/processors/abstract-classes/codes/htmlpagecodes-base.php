<?php

use PoPCMSSchema\Pages\Facades\PageTypeAPIFacade;

abstract class PoP_Module_Processor_HTMLPageCodesBase extends PoP_Module_Processor_HTMLCodesBase
{
    public function getCode(array $componentVariation, array &$props)
    {
        $pageTypeAPI = PageTypeAPIFacade::getInstance();
        return $pageTypeAPI->getContent($this->getPageId($componentVariation));
    }

    public function getPageId(array $componentVariation)
    {
        return null;
    }
}
