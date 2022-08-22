<?php

class PoP_Module_Processor_CustomFullUserTitleLayouts extends PoP_Module_Processor_FullUserTitleLayoutsBase
{
    public final const COMPONENT_LAYOUT_FULLUSERTITLE = 'singlelayout-fullusertitle';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_FULLUSERTITLE,
        );
    }
}



