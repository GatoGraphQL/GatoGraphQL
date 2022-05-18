<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_Tables extends PoP_Module_Processor_TablesBase
{
    public final const MODULE_TABLE_MYCONTENT = 'table-mycontent';
    public final const MODULE_TABLE_MYHIGHLIGHTS = 'table-myhighlights';
    public final const MODULE_TABLE_MYPOSTS = 'table-myposts';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_TABLE_MYCONTENT],
            [self::class, self::MODULE_TABLE_MYHIGHLIGHTS],
            [self::class, self::MODULE_TABLE_MYPOSTS],
        );
    }

    public function getInnerSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_TABLE_MYCONTENT:
            case self::MODULE_TABLE_MYHIGHLIGHTS:
            case self::MODULE_TABLE_MYPOSTS:
                $inners = array(
                    self::MODULE_TABLE_MYCONTENT => [PoP_Module_Processor_TableInners::class, PoP_Module_Processor_TableInners::MODULE_TABLEINNER_MYCONTENT],
                    self::MODULE_TABLE_MYHIGHLIGHTS => [PoP_Module_Processor_TableInners::class, PoP_Module_Processor_TableInners::MODULE_TABLEINNER_MYHIGHLIGHTS],
                    self::MODULE_TABLE_MYPOSTS => [PoP_Module_Processor_TableInners::class, PoP_Module_Processor_TableInners::MODULE_TABLEINNER_MYPOSTS],
                );

                return $inners[$module[1]];
        }

        return parent::getInnerSubmodule($module);
    }

    public function getHeaderTitles(array $module)
    {
        $ret = parent::getHeaderTitles($module);

        switch ($module[1]) {
            case self::MODULE_TABLE_MYCONTENT:
                $ret[] = TranslationAPIFacade::getInstance()->__('Content', 'poptheme-wassup');
                $ret[] = TranslationAPIFacade::getInstance()->__('Status', 'poptheme-wassup');
                break;

            case self::MODULE_TABLE_MYHIGHLIGHTS:
                $ret[] = TranslationAPIFacade::getInstance()->__('Highlight', 'poptheme-wassup');
                $ret[] = TranslationAPIFacade::getInstance()->__('Date', 'poptheme-wassup');
                $ret[] = TranslationAPIFacade::getInstance()->__('Status', 'poptheme-wassup');
                break;

            case self::MODULE_TABLE_MYPOSTS:
                $ret[] = TranslationAPIFacade::getInstance()->__('Post', 'poptheme-wassup');
                $ret[] = TranslationAPIFacade::getInstance()->__('Date', 'poptheme-wassup');
                $ret[] = TranslationAPIFacade::getInstance()->__('Status', 'poptheme-wassup');
                break;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_TABLE_MYHIGHLIGHTS:
                $this->appendProp($module, $props, 'class', 'table-myhighlights');
                break;
        }

        parent::initModelProps($module, $props);
    }
}


