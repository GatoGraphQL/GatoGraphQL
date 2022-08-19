<?php

class PoP_Module_Processor_AuthorContentLayouts extends PoP_Module_Processor_AuthorContentLayoutsBase
{
    public final const COMPONENT_LAYOUTAUTHOR_CONTENT = 'layoutauthor-content';
    public final const COMPONENT_LAYOUTAUTHOR_LIMITEDCONTENT = 'layoutauthor-limitedcontent';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUTAUTHOR_LIMITEDCONTENT,
            self::COMPONENT_LAYOUTAUTHOR_CONTENT,
        );
    }

    public function getDescriptionMaxlength(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUTAUTHOR_LIMITEDCONTENT:
                return 300;
        }

        return parent::getDescriptionMaxlength($component, $props);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUTAUTHOR_CONTENT:
                $this->appendProp($component, $props, 'class', 'layoutauthor readable clearfix');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



