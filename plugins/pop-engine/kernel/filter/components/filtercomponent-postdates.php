<?php
namespace PoP\Engine;

abstract class FilterComponent_PostDatesBase extends FilterComponentBase
{
    public function getPostdates($filter)
    {
        return $this->getFilterinputValue($filter);
    }
}
