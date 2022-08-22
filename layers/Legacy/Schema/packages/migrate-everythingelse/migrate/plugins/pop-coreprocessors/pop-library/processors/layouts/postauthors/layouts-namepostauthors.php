<?php

class PoP_Module_Processor_PostAuthorNameLayouts extends PoP_Module_Processor_PostAuthorNameLayoutsBase
{
    public final const COMPONENT_LAYOUTPOST_AUTHORNAME = 'layoutpost-authorname';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUTPOST_AUTHORNAME,
        );
    }
}



