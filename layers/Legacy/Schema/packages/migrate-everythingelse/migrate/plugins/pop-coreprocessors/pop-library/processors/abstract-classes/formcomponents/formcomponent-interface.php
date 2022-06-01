<?php

use PoP\ComponentModel\ComponentProcessors\FormComponentComponentProcessorInterface as UpstreamFormComponentComponentProcessorInterface;

interface FormComponentComponentProcessorInterface extends UpstreamFormComponentComponentProcessorInterface
{
    public function getLabel(\PoP\ComponentModel\Component\Component $component, array &$props);
}
