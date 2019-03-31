<?php
namespace PoP\Engine;

interface DataloadQueryArgsFilter
{
    public function getValue($module);
    public function filterDataloadQueryArgs(array &$query, $module, $value);
}
