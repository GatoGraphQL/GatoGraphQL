<?php

class PoP_Module_Processor_AuthorContentLayouts extends PoP_Module_Processor_AuthorContentLayoutsBase
{
    public final const MODULE_LAYOUTAUTHOR_CONTENT = 'layoutauthor-content';
    public final const MODULE_LAYOUTAUTHOR_LIMITEDCONTENT = 'layoutauthor-limitedcontent';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUTAUTHOR_LIMITEDCONTENT],
            [self::class, self::MODULE_LAYOUTAUTHOR_CONTENT],
        );
    }

    public function getDescriptionMaxlength(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_LAYOUTAUTHOR_LIMITEDCONTENT:
                return 300;
        }

        return parent::getDescriptionMaxlength($component, $props);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::MODULE_LAYOUTAUTHOR_CONTENT:
                $this->appendProp($component, $props, 'class', 'layoutauthor readable clearfix');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



