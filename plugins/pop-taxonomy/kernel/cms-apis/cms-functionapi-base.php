<?php
namespace PoP\Taxonomy;

abstract class FunctionAPI_Base implements FunctionAPI
{
    public function __construct()
    {
        FunctionAPI_Factory::setInstance($this);
    }
}
