<?php

abstract class PoP_Module_Processor_AddEditContentBlocksBase extends PoP_Module_Processor_BlocksBase
{
    protected function isCreate(array $module)
    {
        return null;
    }
    protected function isUpdate(array $module)
    {
        return null;
    }
    protected function showDisabledLayerIfCheckpointFailed(array $module, array &$props)
    {
        if ($this->isUpdate($module)) {
            return true;
        }

        return parent::showDisabledLayerIfCheckpointFailed($module, $props);
    }

    protected function getControlgroupTopSubmodule(array $module)
    {
        if ($this->isUpdate($module)) {
            return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_EDITPOST];
        } elseif ($this->isCreate($module)) {
            return [PoP_Module_Processor_CustomControlGroups::class, PoP_Module_Processor_CustomControlGroups::MODULE_CONTROLGROUP_CREATEPOST];
        }
        
        return parent::getControlgroupTopSubmodule($module);
    }
}
