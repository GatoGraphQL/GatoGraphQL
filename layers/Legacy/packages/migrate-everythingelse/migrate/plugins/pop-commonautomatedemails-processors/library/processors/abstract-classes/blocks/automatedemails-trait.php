<?php

trait AutomatedEmailsBlocksBaseTrait
{
    public function getTitle(array $module, array &$props)
    {
        return '';
    }

    protected function showDisabledLayer(array $module, array &$props)
    {
        return false;
    }

    public function getModelPropsForDescendantModules(array $module, array &$props): array
    {
        $ret = parent::getModelPropsForDescendantModules($module, $props);

        $ret['convert-classes-to-styles'] = true;

        return $ret;
    }
}
