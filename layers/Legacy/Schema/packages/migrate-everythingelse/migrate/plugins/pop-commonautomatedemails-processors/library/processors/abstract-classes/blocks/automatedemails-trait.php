<?php

trait AutomatedEmailsBlocksBaseTrait
{
    public function getTitle(array $componentVariation, array &$props)
    {
        return '';
    }

    protected function showDisabledLayer(array $componentVariation, array &$props)
    {
        return false;
    }

    public function getModelPropsForDescendantComponentVariations(array $componentVariation, array &$props): array
    {
        $ret = parent::getModelPropsForDescendantComponentVariations($componentVariation, $props);

        $ret['convert-classes-to-styles'] = true;

        return $ret;
    }
}
