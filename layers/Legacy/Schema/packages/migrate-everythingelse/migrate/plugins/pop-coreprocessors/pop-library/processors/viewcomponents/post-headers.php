<?php

class PoP_Module_Processor_PostViewComponentHeaders extends PoP_Module_Processor_PostViewComponentHeadersBase
{
    public final const COMPONENT_VIEWCOMPONENT_HEADER_POST = 'viewcomponent-header-post-';
    public final const COMPONENT_VIEWCOMPONENT_HEADER_POST_URL = 'viewcomponent-header-post-url';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_VIEWCOMPONENT_HEADER_POST,
            self::COMPONENT_VIEWCOMPONENT_HEADER_POST_URL,
        );
    }

    public function headerShowUrl(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_VIEWCOMPONENT_HEADER_POST_URL:
                return true;
        }

        return parent::headerShowUrl($component, $props);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_VIEWCOMPONENT_HEADER_POST_URL:
                $this->appendProp($component, $props, 'class', 'alert alert-warning alert-sm');
                break;
        }

        parent::initModelProps($component, $props);
    }
}


