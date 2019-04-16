<?php
namespace PoP\QueriedObject;

abstract class CMSRoutingStateBase implements CMSRoutingState
{
    public function __construct()
    {
        CMSRoutingState_Factory::setInstance($this);
    }
}
