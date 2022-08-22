<?php

class PoP_Module_Processor_UserQuickLinkLayouts extends PoP_Module_Processor_UserQuickLinkLayoutsBase
{
    public final const COMPONENT_LAYOUTUSER_QUICKLINKS = 'layoutuser-quicklinks';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUTUSER_QUICKLINKS,
        );
    }
}



