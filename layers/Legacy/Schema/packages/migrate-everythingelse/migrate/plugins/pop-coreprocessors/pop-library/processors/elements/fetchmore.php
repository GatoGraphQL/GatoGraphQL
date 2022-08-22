<?php

class PoP_Module_Processor_FetchMore extends PoP_Module_Processor_FetchMoreBase
{
    public final const COMPONENT_FETCHMORE = 'fetchmore';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FETCHMORE,
        );
    }
}



