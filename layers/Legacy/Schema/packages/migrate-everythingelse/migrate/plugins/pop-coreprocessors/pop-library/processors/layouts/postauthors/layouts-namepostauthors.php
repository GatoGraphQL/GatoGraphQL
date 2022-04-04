<?php

class PoP_Module_Processor_PostAuthorNameLayouts extends PoP_Module_Processor_PostAuthorNameLayoutsBase
{
    public final const MODULE_LAYOUTPOST_AUTHORNAME = 'layoutpost-authorname';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUTPOST_AUTHORNAME],
        );
    }
}



