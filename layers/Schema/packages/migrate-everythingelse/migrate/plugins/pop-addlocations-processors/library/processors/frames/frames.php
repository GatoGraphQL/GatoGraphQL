<?php

class GD_EM_Module_Processor_CreateLocationFrames extends GD_EM_Module_Processor_CreateLocationFramesBase
{
    public const MODULE_FRAME_CREATELOCATIONMAP = 'em-frame-createlocationmap';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FRAME_CREATELOCATIONMAP],
        );
    }
}



