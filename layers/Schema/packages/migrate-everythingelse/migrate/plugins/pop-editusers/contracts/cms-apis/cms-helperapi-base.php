<?php
namespace PoP\EditUsers;

abstract class HelperAPI_Base implements HelperAPI
{
    public function __construct()
    {
        HelperAPIFactory::setInstance($this);
    }
}
