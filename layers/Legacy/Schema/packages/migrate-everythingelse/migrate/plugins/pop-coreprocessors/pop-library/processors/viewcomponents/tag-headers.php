<?php

class PoP_Module_Processor_TagViewComponentHeaders extends PoP_Module_Processor_TagViewComponentHeadersBase
{
    public final const MODULE_VIEWCOMPONENT_HEADER_TAG = 'viewcomponent-header-tag';
    public final const MODULE_VIEWCOMPONENT_HEADER_TAG_URL = 'viewcomponent-header-tag-url';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_VIEWCOMPONENT_HEADER_TAG],
            [self::class, self::MODULE_VIEWCOMPONENT_HEADER_TAG_URL],
        );
    }

    public function headerShowUrl(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_VIEWCOMPONENT_HEADER_TAG_URL:
                // Add the URL in the header? Sometimes yes (eg: Addon) sometimes not (eg: modal)
                return true;
        }

        return parent::headerShowUrl($module, $props);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_VIEWCOMPONENT_HEADER_TAG_URL:
                $this->appendProp($module, $props, 'class', 'alert alert-warning alert-sm');
                break;
        }

        parent::initModelProps($module, $props);
    }
}


