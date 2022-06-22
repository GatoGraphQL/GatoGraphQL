<?php

class GD_AAL_Module_Processor_FunctionsContents extends PoP_Module_Processor_ContentsBase
{
    public final const COMPONENT_CONTENT_MARKNOTIFICATIONASREAD = 'content-marknotificationasread';
    public final const COMPONENT_CONTENT_MARKNOTIFICATIONASUNREAD = 'content-marknotificationasunread';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_CONTENT_MARKNOTIFICATIONASREAD,
            self::COMPONENT_CONTENT_MARKNOTIFICATIONASUNREAD,
        );
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $inners = array(
            self::COMPONENT_CONTENT_MARKNOTIFICATIONASREAD => [GD_AAL_Module_Processor_FunctionsContentMultipleInners::class, GD_AAL_Module_Processor_FunctionsContentMultipleInners::COMPONENT_CONTENTINNER_MARKNOTIFICATIONASREAD],
            self::COMPONENT_CONTENT_MARKNOTIFICATIONASUNREAD => [GD_AAL_Module_Processor_FunctionsContentMultipleInners::class, GD_AAL_Module_Processor_FunctionsContentMultipleInners::COMPONENT_CONTENTINNER_MARKNOTIFICATIONASUNREAD],
        );
        if ($inner = $inners[$component->name] ?? null) {
            return $inner;
        }

        return parent::getInnerSubcomponent($component);
    }
}


