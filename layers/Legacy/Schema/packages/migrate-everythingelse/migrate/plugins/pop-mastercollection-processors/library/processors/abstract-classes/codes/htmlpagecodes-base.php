<?php

use PoPCMSSchema\Pages\Facades\PageTypeAPIFacade;

abstract class PoP_Module_Processor_HTMLPageCodesBase extends PoP_Module_Processor_HTMLCodesBase
{
    public function getCode(array $component, array &$props)
    {
        $pageTypeAPI = PageTypeAPIFacade::getInstance();
        return $pageTypeAPI->getContent($this->getPageId($component));
    }

    public function getPageId(array $component)
    {
        return null;
    }
}
