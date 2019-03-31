<?php
namespace PoP\CMS;

abstract class ContentAPI_Base implements ContentAPI
{
    public function __construct()
    {
        ContentAPI_Factory::setInstance($this);
    }
}
