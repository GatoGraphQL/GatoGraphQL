<?php

class PoP_Module_Processor_AuthorContentLayouts extends PoP_Module_Processor_AuthorContentLayoutsBase
{
    public final const MODULE_LAYOUTAUTHOR_CONTENT = 'layoutauthor-content';
    public final const MODULE_LAYOUTAUTHOR_LIMITEDCONTENT = 'layoutauthor-limitedcontent';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUTAUTHOR_LIMITEDCONTENT],
            [self::class, self::MODULE_LAYOUTAUTHOR_CONTENT],
        );
    }

    public function getDescriptionMaxlength(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUTAUTHOR_LIMITEDCONTENT:
                return 300;
        }

        return parent::getDescriptionMaxlength($componentVariation, $props);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUTAUTHOR_CONTENT:
                $this->appendProp($componentVariation, $props, 'class', 'layoutauthor readable clearfix');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



