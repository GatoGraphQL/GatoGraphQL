<?php

class GD_AAL_Module_Processor_FunctionsContents extends PoP_Module_Processor_ContentsBase
{
    public final const MODULE_CONTENT_MARKNOTIFICATIONASREAD = 'content-marknotificationasread';
    public final const MODULE_CONTENT_MARKNOTIFICATIONASUNREAD = 'content-marknotificationasunread';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_CONTENT_MARKNOTIFICATIONASREAD],
            [self::class, self::COMPONENT_CONTENT_MARKNOTIFICATIONASUNREAD],
        );
    }

    public function getInnerSubmodule(array $component)
    {
        $inners = array(
            self::COMPONENT_CONTENT_MARKNOTIFICATIONASREAD => [GD_AAL_Module_Processor_FunctionsContentMultipleInners::class, GD_AAL_Module_Processor_FunctionsContentMultipleInners::COMPONENT_CONTENTINNER_MARKNOTIFICATIONASREAD],
            self::COMPONENT_CONTENT_MARKNOTIFICATIONASUNREAD => [GD_AAL_Module_Processor_FunctionsContentMultipleInners::class, GD_AAL_Module_Processor_FunctionsContentMultipleInners::COMPONENT_CONTENTINNER_MARKNOTIFICATIONASUNREAD],
        );
        if ($inner = $inners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($component);
    }
}


