<?php
namespace PoP\Engine;

abstract class FilterComponent_CategoriesBase extends FilterComponentBase
{
    public function getCategories($filter)
    {
        return $this->getFilterinputValue($filter);
    }
}
