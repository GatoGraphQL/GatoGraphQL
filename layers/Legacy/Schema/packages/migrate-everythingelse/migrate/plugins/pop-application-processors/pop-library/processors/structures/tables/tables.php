<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_Tables extends PoP_Module_Processor_TablesBase
{
    public final const COMPONENT_TABLE_MYCONTENT = 'table-mycontent';
    public final const COMPONENT_TABLE_MYHIGHLIGHTS = 'table-myhighlights';
    public final const COMPONENT_TABLE_MYPOSTS = 'table-myposts';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_TABLE_MYCONTENT,
            self::COMPONENT_TABLE_MYHIGHLIGHTS,
            self::COMPONENT_TABLE_MYPOSTS,
        );
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_TABLE_MYCONTENT:
            case self::COMPONENT_TABLE_MYHIGHLIGHTS:
            case self::COMPONENT_TABLE_MYPOSTS:
                $inners = array(
                    self::COMPONENT_TABLE_MYCONTENT => [PoP_Module_Processor_TableInners::class, PoP_Module_Processor_TableInners::COMPONENT_TABLEINNER_MYCONTENT],
                    self::COMPONENT_TABLE_MYHIGHLIGHTS => [PoP_Module_Processor_TableInners::class, PoP_Module_Processor_TableInners::COMPONENT_TABLEINNER_MYHIGHLIGHTS],
                    self::COMPONENT_TABLE_MYPOSTS => [PoP_Module_Processor_TableInners::class, PoP_Module_Processor_TableInners::COMPONENT_TABLEINNER_MYPOSTS],
                );

                return $inners[$component->name];
        }

        return parent::getInnerSubcomponent($component);
    }

    public function getHeaderTitles(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = parent::getHeaderTitles($component);

        switch ($component->name) {
            case self::COMPONENT_TABLE_MYCONTENT:
                $ret[] = TranslationAPIFacade::getInstance()->__('Content', 'poptheme-wassup');
                $ret[] = TranslationAPIFacade::getInstance()->__('Status', 'poptheme-wassup');
                break;

            case self::COMPONENT_TABLE_MYHIGHLIGHTS:
                $ret[] = TranslationAPIFacade::getInstance()->__('Highlight', 'poptheme-wassup');
                $ret[] = TranslationAPIFacade::getInstance()->__('Date', 'poptheme-wassup');
                $ret[] = TranslationAPIFacade::getInstance()->__('Status', 'poptheme-wassup');
                break;

            case self::COMPONENT_TABLE_MYPOSTS:
                $ret[] = TranslationAPIFacade::getInstance()->__('Post', 'poptheme-wassup');
                $ret[] = TranslationAPIFacade::getInstance()->__('Date', 'poptheme-wassup');
                $ret[] = TranslationAPIFacade::getInstance()->__('Status', 'poptheme-wassup');
                break;
        }

        return $ret;
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_TABLE_MYHIGHLIGHTS:
                $this->appendProp($component, $props, 'class', 'table-myhighlights');
                break;
        }

        parent::initModelProps($component, $props);
    }
}


