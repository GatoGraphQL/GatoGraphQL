<?php

class PoP_Module_Processor_UserViewComponentHeaders extends PoP_Module_Processor_UserViewComponentHeadersBase
{
    public final const COMPONENT_VIEWCOMPONENT_HEADER_USER = 'viewcomponent-header-user';
    public final const COMPONENT_VIEWCOMPONENT_HEADER_USER_URL = 'viewcomponent-header-user-url';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_VIEWCOMPONENT_HEADER_USER],
            [self::class, self::COMPONENT_VIEWCOMPONENT_HEADER_USER_URL],
        );
    }

    public function headerShowUrl(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_VIEWCOMPONENT_HEADER_USER_URL:
                // Add the URL in the header? Sometimes yes (eg: Addon) sometimes not (eg: modal)
                return true;
        }

        return parent::headerShowUrl($component, $props);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_VIEWCOMPONENT_HEADER_USER_URL:
                $this->appendProp($component, $props, 'class', 'alert alert-warning alert-sm');
                break;
        }

        parent::initModelProps($component, $props);
    }
}


