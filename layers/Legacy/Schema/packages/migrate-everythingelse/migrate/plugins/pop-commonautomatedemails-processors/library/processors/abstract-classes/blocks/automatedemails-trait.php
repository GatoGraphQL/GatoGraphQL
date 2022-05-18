<?php

trait AutomatedEmailsBlocksBaseTrait
{
    public function getTitle(array $component, array &$props)
    {
        return '';
    }

    protected function showDisabledLayer(array $component, array &$props)
    {
        return false;
    }

    public function getModelPropsForDescendantComponents(array $component, array &$props): array
    {
        $ret = parent::getModelPropsForDescendantComponents($component, $props);

        $ret['convert-classes-to-styles'] = true;

        return $ret;
    }
}
