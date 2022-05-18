<?php

abstract class PoP_Module_Processor_AddEditContentBlocksBase extends PoP_Module_Processor_BlocksBase
{
    protected function isCreate(array $componentVariation)
    {
        return null;
    }
    protected function isUpdate(array $componentVariation)
    {
        return null;
    }
    protected function showDisabledLayerIfCheckpointFailed(array $componentVariation, array &$props)
    {
        if ($this->isUpdate($componentVariation)) {
            return true;
        }

        return parent::showDisabledLayerIfCheckpointFailed($componentVariation, $props);
    }

    protected function getControlgroupTopSubmodule(array $componentVariation)
    {
        if ($this->isUpdate($componentVariation)) {
            return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_EDITPOST];
        } elseif ($this->isCreate($componentVariation)) {
            return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_CREATEPOST];
        }
        
        return parent::getControlgroupTopSubmodule($componentVariation);
    }
}
