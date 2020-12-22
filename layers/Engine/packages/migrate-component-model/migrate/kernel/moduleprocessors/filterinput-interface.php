<?php
namespace PoP\ComponentModel;

interface FilterInput
{
    public function filterDataloadQueryArgs(array $filterInput, array &$query, $value);
}
