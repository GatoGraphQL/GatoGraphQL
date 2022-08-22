<?php

class PoP_ContentPostLinks_Module_Processor_Layouts extends Wassup_Module_Processor_LinkAccessLayoutsBase
{
    public final const COMPONENT_LAYOUT_LINK_ACCESS = 'layout-link-access';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_LINK_ACCESS,
        );
    }
}



