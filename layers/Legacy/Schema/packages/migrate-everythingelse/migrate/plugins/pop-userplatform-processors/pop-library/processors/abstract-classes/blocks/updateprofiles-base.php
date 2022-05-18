<?php

abstract class PoP_Module_Processor_UpdateProfileBlocksBase extends PoP_Module_Processor_BlocksBase
{
    protected function showDisabledLayerIfCheckpointFailed(array $componentVariation, array &$props)
    {
        return true;
    }
}
