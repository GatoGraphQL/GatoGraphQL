<?php

class PoP_Module_Processor_UserCardLayouts extends PoP_Module_Processor_UserCardLayoutsBase
{
    public final const COMPONENT_LAYOUTUSER_CARD = 'layoutuser-card';
    public final const COMPONENT_LAYOUTUSER_FILTERCARD = 'layoutuser-filtercard';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUTUSER_CARD,
            self::COMPONENT_LAYOUTUSER_FILTERCARD,
        );
    }
}



