<?php
namespace PoP\CMS;

abstract class HooksAPI_Base implements HooksAPI
{
    public function __construct()
    {
        HooksAPI_Factory::setInstance($this);
    }
}
