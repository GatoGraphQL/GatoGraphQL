<?php
namespace PoP\CMS;

abstract class FunctionAPI_Base
{
    public function __construct()
    {
        FunctionAPI_Factory::setInstance($this);
    }
}
