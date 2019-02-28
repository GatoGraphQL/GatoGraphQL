<?php
namespace PoP\Engine;

abstract class FilterComponent_PostStatusBase extends FilterComponentBase
{
    public function getPoststatus($filter)
    {
        return $this->getFilterinputValue($filter);
    }
}
