<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_Custom_EM_Module_Processor_Tables extends PoP_Module_Processor_TablesBase
{
    public final const COMPONENT_TABLE_MYLOCATIONPOSTS = 'table-mylocationposts';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_TABLE_MYLOCATIONPOSTS],
        );
    }

    public function getInnerSubcomponent(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_TABLE_MYLOCATIONPOSTS:
                $inners = array(
                    self::COMPONENT_TABLE_MYLOCATIONPOSTS => [GD_Custom_EM_Module_Processor_TableInners::class, GD_Custom_EM_Module_Processor_TableInners::COMPONENT_TABLEINNER_MYLOCATIONPOSTS],
                );

                return $inners[$component[1]];
        }

        return parent::getInnerSubcomponent($component);
    }

    public function getHeaderTitles(array $component)
    {
        $ret = parent::getHeaderTitles($component);

        switch ($component[1]) {
            case self::COMPONENT_TABLE_MYLOCATIONPOSTS:
                $ret[] = PoP_LocationPosts_PostNameUtils::getNameUc();
                $ret[] = TranslationAPIFacade::getInstance()->__('Date', 'pop-locationpostscreation-processors');
                $ret[] = TranslationAPIFacade::getInstance()->__('Status', 'pop-locationpostscreation-processors');
                break;
        }
    
        return $ret;
    }
}


