<?php
namespace PoP\Engine;

interface DataloadingModule extends FormattableModule
{
    public function getFilterModule($module);
}
