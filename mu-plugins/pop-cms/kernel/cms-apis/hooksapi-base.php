<?php
namespace PoP\CMS;

abstract class HooksAPI_Base
{
    public function __construct()
    {
        HooksAPI_Factory::setInstance($this);
    }
}
