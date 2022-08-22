<?php

class PoP_Module_Processor_PostStatusLayouts extends PoP_Module_Processor_PostStatusLayoutsBase
{
    public final const COMPONENT_LAYOUTPOST_STATUS = 'layoutpost-status';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUTPOST_STATUS,
        );
    }
}



