<?php

class PoP_Module_Processor_PostDateLayouts extends PoP_Module_Processor_PostDateLayoutsBase
{
    public final const COMPONENT_LAYOUTPOST_DATE = 'layoutpost-date';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUTPOST_DATE,
        );
    }
}



