<?php

trait AutomatedEmailsBlocksBaseTrait
{
    public function getTitle(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return '';
    }

    protected function showDisabledLayer(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return false;
    }

    public function getModelPropsForDescendantComponents(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getModelPropsForDescendantComponents($component, $props);

        $ret['convert-classes-to-styles'] = true;

        return $ret;
    }
}
