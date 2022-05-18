<?php

class PoP_Module_Processor_PostViewComponentHeaders extends PoP_Module_Processor_PostViewComponentHeadersBase
{
    public final const MODULE_VIEWCOMPONENT_HEADER_POST = 'viewcomponent-header-post-';
    public final const MODULE_VIEWCOMPONENT_HEADER_POST_URL = 'viewcomponent-header-post-url';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_VIEWCOMPONENT_HEADER_POST],
            [self::class, self::MODULE_VIEWCOMPONENT_HEADER_POST_URL],
        );
    }

    public function headerShowUrl(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_VIEWCOMPONENT_HEADER_POST_URL:
                return true;
        }

        return parent::headerShowUrl($module, $props);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_VIEWCOMPONENT_HEADER_POST_URL:
                $this->appendProp($module, $props, 'class', 'alert alert-warning alert-sm');
                break;
        }

        parent::initModelProps($module, $props);
    }
}


