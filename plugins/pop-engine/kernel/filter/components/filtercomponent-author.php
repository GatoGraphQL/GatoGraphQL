<?php
namespace PoP\Engine;

abstract class FilterComponent_AuthorBase extends FilterComponentBase
{
    public function getAuthor($filter)
    {
        return $this->getFilterinputValue($filter);
    }
}
