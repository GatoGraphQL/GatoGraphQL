<?php

use PoP\ComponentModel\ComponentProcessors\FormComponentComponentProcessorInterface as UpstreamFormComponentComponentProcessorInterface;

interface FormComponentComponentProcessorInterface extends UpstreamFormComponentComponentProcessorInterface
{
    public function getLabel(array $componentVariation, array &$props);
}
