<?php

class PoP_Module_Processor_AppendCommentLayouts extends PoP_Module_Processor_AppendCommentLayoutsBase
{
    public final const COMPONENT_SCRIPT_APPENDCOMMENT = 'script-append-comment';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_SCRIPT_APPENDCOMMENT,
        );
    }
}



