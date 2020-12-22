<?php
namespace PoP\EditPosts;

abstract class HelperAPI_Base implements HelperAPI
{
    public function __construct()
    {
        HelperAPIFactory::setInstance($this);
    }
}
