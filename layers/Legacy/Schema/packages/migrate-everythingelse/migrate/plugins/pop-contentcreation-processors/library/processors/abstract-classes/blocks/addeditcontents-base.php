<?php

abstract class PoP_Module_Processor_AddEditContentBlocksBase extends PoP_Module_Processor_BlocksBase
{
    protected function isCreate(array $component)
    {
        return null;
    }
    protected function isUpdate(array $component)
    {
        return null;
    }
    protected function showDisabledLayerIfCheckpointFailed(array $component, array &$props)
    {
        if ($this->isUpdate($component)) {
            return true;
        }

        return parent::showDisabledLayerIfCheckpointFailed($component, $props);
    }

    protected function getControlgroupTopSubmodule(array $component)
    {
        if ($this->isUpdate($component)) {
            return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_EDITPOST];
        } elseif ($this->isCreate($component)) {
            return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_CREATEPOST];
        }
        
        return parent::getControlgroupTopSubmodule($component);
    }
}
