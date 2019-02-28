<?php
namespace PoP\Engine;

abstract class FilterComponent_SearchBase extends FilterComponentBase
{
    public function getSearch($filter)
    {
        return $this->getFilterinputValue($filter);
    }
}
