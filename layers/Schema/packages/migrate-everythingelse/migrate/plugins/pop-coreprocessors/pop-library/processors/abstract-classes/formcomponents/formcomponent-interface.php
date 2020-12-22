<?php

interface FormComponent extends \PoP\ComponentModel\FormComponent
{
    public function getLabel(array $module, array &$props);
}
