<?php
namespace PoP\CMS;

abstract class ObjectPropertyResolver_Base
{
    public function __construct()
    {
        ObjectPropertyResolver_Factory::setInstance($this);
    }
}
