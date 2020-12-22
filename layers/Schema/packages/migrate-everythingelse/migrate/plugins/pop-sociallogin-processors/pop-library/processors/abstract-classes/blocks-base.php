<?php

abstract class GD_WSL_Module_Processor_NetworkLinkBlocksBase extends PoP_Module_Processor_BlocksBase
{
    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);

        $this->addJsmethod($ret, 'addDomainClass');
        
        return $ret;
    }
    public function getImmutableJsconfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableJsconfiguration($module, $props);

        // For function addDomainClass
        $ret['addDomainClass']['prefix'] = 'visible-notloggedin-';

        return $ret;
    }

    public function initModelProps(array $module, array &$props)
    {

        // Visible only if the user not logged in
        $this->appendProp($module, $props, 'class', 'visible-notloggedin');

        // Add the LoginBlock target, as to add the Disabled Layer on top
        // while waiting for the server authenticating the FB/Twitter user
        // Allow to override this value. By default, it is this same block, but can be
        // the containing GROUP_LOGIN
        $blocktarget = '#'./*$props['block-id']*/$this->getFrontendId($module, $props);
        $this->setProp($module, $props, 'loginblock-target', $blocktarget);
        $this->mergeProp(
            $module,
            $props,
            'params',
            array(
                'data-loginblock' => $this->getProp($module, $props, 'loginblock-target')
            )
        );
        parent::initModelProps($module, $props);
    }
}
