<?php
namespace PoP\CMS;

abstract class ObjectPropertyResolver_Base implements ObjectPropertyResolver
{
    public function __construct()
    {
        ObjectPropertyResolver_Factory::setInstance($this);
    }
}
