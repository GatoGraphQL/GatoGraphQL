<?php

class PoP_Module_Processor_PostCardLayouts extends PoP_Module_Processor_PostCardLayoutsBase
{
    public final const COMPONENT_LAYOUTPOST_CARD = 'layoutpost-card';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUTPOST_CARD,
        );
    }
}



