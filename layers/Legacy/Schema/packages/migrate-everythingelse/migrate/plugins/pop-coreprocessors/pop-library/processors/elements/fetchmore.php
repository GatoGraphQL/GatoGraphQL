<?php

class PoP_Module_Processor_FetchMore extends PoP_Module_Processor_FetchMoreBase
{
    public final const MODULE_FETCHMORE = 'fetchmore';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FETCHMORE],
        );
    }
}



