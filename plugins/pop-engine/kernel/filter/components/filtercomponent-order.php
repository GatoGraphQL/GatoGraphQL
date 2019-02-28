<?php
namespace PoP\Engine;

abstract class FilterComponent_OrderBase extends FilterComponentBase
{
    public function getOrder($filter)
    {
        return $this->getFilterinputValue($filter);
    }
}
