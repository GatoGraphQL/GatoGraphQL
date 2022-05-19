<?php

class PoP_Module_Processor_PostAuthorNameLayouts extends PoP_Module_Processor_PostAuthorNameLayoutsBase
{
    public final const COMPONENT_LAYOUTPOST_AUTHORNAME = 'layoutpost-authorname';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUTPOST_AUTHORNAME],
        );
    }
}



