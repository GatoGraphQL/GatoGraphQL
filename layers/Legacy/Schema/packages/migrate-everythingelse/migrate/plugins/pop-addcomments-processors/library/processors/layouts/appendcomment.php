<?php

class PoP_Module_Processor_AppendCommentLayouts extends PoP_Module_Processor_AppendCommentLayoutsBase
{
    public final const MODULE_SCRIPT_APPENDCOMMENT = 'script-append-comment';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_SCRIPT_APPENDCOMMENT],
        );
    }
}



