<?php
namespace PoP\CMSModel;

trait FunctionAPI_Trait
{
    public function currentUserCan($capability)
    {
        $vars = \PoP\Engine\Engine_Vars::getVars();
        return $this->userCan($vars['global-userstate']['current-user-id'], $capability);
    }
}
