<?php
namespace PoP\CMS;

abstract class CMSRoutingStateBase implements CMSRoutingState
{
    public function __construct()
    {
        CMSRoutingState_Factory::setInstance($this);
    }
    
    public function getNature()
    {
    	// By default, everything is a standard route
    	return POP_NATURE_STANDARD;
    }
}
