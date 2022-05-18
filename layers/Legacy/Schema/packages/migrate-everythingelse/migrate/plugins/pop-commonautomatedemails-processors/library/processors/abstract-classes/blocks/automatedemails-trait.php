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

    public function getModelPropsForDescendantComponentVariations(array $module, array &$props): array
    {
        $ret = parent::getModelPropsForDescendantComponentVariations($module, $props);

        $ret['convert-classes-to-styles'] = true;

        return $ret;
    }
}
